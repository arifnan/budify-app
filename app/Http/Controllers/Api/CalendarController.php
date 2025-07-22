<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Menyediakan ringkasan transaksi harian untuk bulan tertentu.
     */
    public function getMonthlySummary(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
        ]);

        $user = Auth::user();
        $month = $request->month;
        $year = $request->year;

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Mengambil total pemasukan dan pengeluaran, dikelompokkan per hari
        $transactions = $user->transactions()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income"),
                DB::raw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense")
            )
            ->groupBy('date')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('j'); // Menggunakan tanggal (1-31) sebagai key
            });

        return response()->json($transactions);
    }
}
