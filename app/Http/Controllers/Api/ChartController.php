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
     * Menyediakan data pengeluaran harian selama 7 hari terakhir.
     */
    public function getWeeklyExpenses(Request $request)
    {
        $user = Auth::user();
        $endDate = Carbon::today()->endOfDay();
        $startDate = Carbon::today()->subDays(6)->startOfDay();

        // Mengambil total pengeluaran, dikelompokkan per hari
        $expenses = $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('sum(amount) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date'); // Menggunakan tanggal sebagai key untuk memudahkan pencarian

        $chartData = [];
        // Loop selama 7 hari untuk memastikan semua hari ada di data, bahkan jika totalnya 0
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $chartData[] = [
                'label' => $date->format('D'), // Format hari (e.g., Mon, Tue)
                'total' => $expenses[$formattedDate]->total ?? 0,
            ];
        }

        return response()->json($chartData);
    }
}
