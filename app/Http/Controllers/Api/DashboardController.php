<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Pastikan Anda sudah memiliki relasi 'transactions' di model User
        $transactions = $user->transactions;

        // --- Kategori Pengeluaran (Pastikan ini sesuai dengan yang Anda definisikan) ---
        $needsCategories = ['Konsumsi/Makan', 'Kos/Tempat Tinggal', 'Transportasi', 'Kebutuhan Akademik', 'Paket Data/Pulsa'];
        $wantsCategories = ['Nongkrong/Jajan', 'Hiburan', 'Hobi', 'Pakaian & Skincare', 'Langganan Digital'];
        $savingsCategories = ['Dana Darurat', 'Pendidikan', 'Tujuan Finansial', 'Kesehatan'];

        // --- Kalkulasi Total ---
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalNeedsExpense = $transactions->where('type', 'expense')->whereIn('category', $needsCategories)->sum('amount');
        $totalWantsExpense = $transactions->where('type', 'expense')->whereIn('category', $wantsCategories)->sum('amount');
        $totalSavingsExpense = $transactions->where('type', 'expense')->whereIn('category', $savingsCategories)->sum('amount');
        
        // --- Alokasi Budget 40-30-30 ---
        $needsBudget = $totalIncome * 0.40;
        $wantsBudget = $totalIncome * 0.30;
        $savingsBudget = $totalIncome * 0.30;

        // --- Hitung Saldo Awal ---
        $remainingNeeds = $needsBudget - $totalNeedsExpense;
        $remainingWants = $wantsBudget - $totalWantsExpense;
        $remainingSavings = $savingsBudget - $totalSavingsExpense;

        // --- Logika Overdraft (Ambil dari tabungan jika boros) ---
        if ($remainingNeeds < 0) {
            $remainingSavings += $remainingNeeds; // Nilainya negatif, jadi ini adalah pengurangan
            $remainingNeeds = 0;
        }
        if ($remainingWants < 0) {
            $remainingSavings += $remainingWants; // Nilainya negatif, jadi ini adalah pengurangan
            $remainingWants = 0;
        }

        // --- Siapkan data untuk dikirim sebagai JSON ---
        $dashboardData = [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'profileImageUrl' => $user->profile_photo_path, // Sesuaikan dengan nama kolom di DB
            ],
            'financialSummary' => [
                'needs' => [
                    'remainingBalance' => round($remainingNeeds),
                    'totalBudget' => round($needsBudget),
                ],
                'wants' => [
                    'remainingBalance' => round($remainingWants),
                    'totalBudget' => round($wantsBudget),
                ],
                'savings' => [
                    'remainingBalance' => round($remainingSavings),
                    'totalBudget' => round($savingsBudget),
                ],
            ],
            'streakCount' => $user->streak_count ?? 0, // Sesuaikan dengan nama kolom di DB
        ];

        return response()->json($dashboardData);
    }
}