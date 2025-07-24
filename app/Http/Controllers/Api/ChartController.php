<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChartController extends Controller
{
    /**
     * Menyediakan data tren pengeluaran DAN ringkasannya untuk Grafik Garis.
     */
    public function getExpenseTrend(Request $request)
    {
        $request->validate(['range' => 'sometimes|in:daily,weekly,monthly,yearly']);
        $range = $request->input('range', 'weekly');
        $user = Auth::user();
        $chartData = [];
        $summary = ['total_income' => 0, 'total_expense' => 0, 'average_expense' => 0];
        $endDate = Carbon::now();

        switch ($range) {
            case 'daily':
                $startDate = Carbon::today();
                $labelFormat = 'H:00';
                $dbGroupBy = DB::raw('HOUR(created_at)');
                $periodCount = 24;
                break;
            case 'monthly':
                $startDate = Carbon::now()->subDays(29);
                $labelFormat = 'd/m';
                $dbGroupBy = DB::raw('DATE(created_at)');
                $periodCount = 30;
                break;
            case 'yearly':
                $startDate = Carbon::now()->subMonths(11)->startOfMonth();
                $labelFormat = 'M';
                $dbGroupBy = DB::raw('DATE_FORMAT(created_at, "%Y-%m")');
                $periodCount = 12;
                break;
            default: // weekly
                $startDate = Carbon::now()->subDays(6);
                $labelFormat = 'D';
                $dbGroupBy = DB::raw('DATE(created_at)');
                $periodCount = 7;
                break;
        }

        // Ambil data transaksi dalam rentang waktu yang ditentukan
        $transactions = $user->transactions()->whereBetween('created_at', [$startDate, $endDate])->get();

        // Kalkulasi Ringkasan
        $summary['total_income'] = $transactions->where('type', 'income')->sum('amount');
        $summary['total_expense'] = $transactions->where('type', 'expense')->sum('amount');
        if ($periodCount > 0) {
            $summary['average_expense'] = $summary['total_expense'] / $periodCount;
        }

        // Olah data untuk grafik
        $expenses = $transactions->where('type', 'expense')
            ->groupBy(function ($date) use ($range) {
                if ($range === 'yearly') return Carbon::parse($date->created_at)->format('Y-m');
                if ($range === 'daily') return Carbon::parse($date->created_at)->format('H');
                return Carbon::parse($date->created_at)->format('Y-m-d');
            })
            ->map(fn ($group) => $group->sum('amount'));

        // Buat label untuk setiap titik di grafik
        if ($range === 'yearly') {
            for ($i = 0; $i < 12; $i++) {
                $date = $startDate->copy()->addMonths($i);
                $key = $date->format('Y-m');
                $chartData[] = ['label' => $date->format('M'), 'total' => $expenses[$key] ?? 0];
            }
        } elseif ($range === 'daily') {
            for ($i = 0; $i < 24; $i++) {
                $date = $startDate->copy()->addHours($i);
                $key = $date->format('H');
                $chartData[] = ['label' => $date->format('H:00'), 'total' => $expenses[$key] ?? 0];
            }
        } else {
            for ($i = 0; $i < $periodCount; $i++) {
                $date = $startDate->copy()->addDays($i);
                $key = $date->format('Y-m-d');
                $chartData[] = ['label' => $date->format($labelFormat), 'total' => $expenses[$key] ?? 0];
            }
        }

        return response()->json([
            'summary' => $summary,
            'chart_data' => $chartData
        ]);
    }

    /**
     * Menyediakan data pengeluaran hari ini per kategori (beserta ikon) untuk Diagram Pie.
     */
    public function getTodaysSpendingByCategory(Request $request)
    {
        $user = Auth::user();
        $spendingData = $user->transactions()
            ->where('type', 'expense')
            ->whereDate('created_at', Carbon::today())
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', 'categories.icon', DB::raw('sum(transactions.amount) as total')) // <-- Tambahkan 'categories.icon'
            ->groupBy('categories.name', 'categories.icon') // <-- Tambahkan 'categories.icon'
            ->having('total', '>', 0)
            ->orderBy('total', 'desc')
            ->get();
        
        return response()->json($spendingData);
    }
}
