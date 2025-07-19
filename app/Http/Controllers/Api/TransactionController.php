<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar transaksi milik pengguna yang sedang login.
     */
    public function index(Request $request)
    {
        // Ambil semua transaksi milik user, urutkan dari yang terbaru
        $transactions = $request->user()->transactions()->latest()->get();
        return response()->json($transactions);
    }

    /**
     * Menyimpan transaksi baru dengan logika pengecekan dana.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => ['required', Rule::in(['income', 'expense'])],
            'category' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $user = Auth::user();
        $amount = (float) $validated['amount'];
        $category = $validated['category'];
        $type = $validated['type'];

        // Jika ini adalah PEMASUKAN, langsung simpan dan selesai.
        if ($type === 'income') {
            $transaction = $user->transactions()->create($validated);
            return response()->json($transaction, 201);
        }

        // =======================================================
        // MULAI LOGIKA PENGELUARAN DENGAN PENGECEKAN DANA
        // =======================================================

        $cycleStartDate = $user->cycle_started_at ?? $user->created_at;

        // Hitung total pemasukan dalam siklus ini
        $totalIncome = $user->transactions()
                            ->where('type', 'income')
                            ->where('created_at', '>=', $cycleStartDate)
                            ->sum('amount');
        
        // Ambil semua pengeluaran dalam siklus ini
        $expenses = $user->transactions()
                         ->where('type', 'expense')
                         ->where('created_at', '>=', $cycleStartDate)
                         ->get();

        // Tentukan budget untuk setiap pos
        $needsBudget = $totalIncome * 0.40;
        $wantsBudget = $totalIncome * 0.30;
        $savingsBudget = $totalIncome * 0.30;

        // Hitung total pengeluaran dari setiap pos
        $needsSpent = $expenses->where('category', 'Needs')->sum('amount');
        $wantsSpent = $expenses->where('category', 'Wants')->sum('amount');
        // Pengeluaran dari 'Savings' juga perlu dihitung
        $savingsSpent = $expenses->where('category', 'Savings')->sum('amount'); 

        // Hitung sisa saldo
        $remainingNeeds = $needsBudget - $needsSpent;
        $remainingWants = $wantsBudget - $wantsSpent;
        
        // Saldo 'Savings' bisa jadi berasal dari alokasi awal + sisa siklus sebelumnya
        $savingsIncomeFromCycleReset = $user->transactions()
                                            ->where('type', 'income')
                                            ->where('category', 'Savings')
                                            ->where('created_at', '>=', $cycleStartDate)
                                            ->sum('amount');
                                            
        $remainingSavings = ($savingsBudget + $savingsIncomeFromCycleReset) - $savingsSpent;


        // Periksa apakah dana mencukupi
        $canAfford = false;
        $deductedFromCategory = $category; // Kategori asli dari mana dana dipotong

        if ($category === 'Needs') {
            if ($remainingNeeds >= $amount) {
                $canAfford = true;
            } elseif (($remainingNeeds + $remainingSavings) >= $amount) {
                // Dana Needs tidak cukup, coba ambil dari Savings
                $canAfford = true;
                $deductedFromCategory = 'Savings';
            }
        } elseif ($category === 'Wants') {
            if ($remainingWants >= $amount) {
                $canAfford = true;
            } elseif (($remainingWants + $remainingSavings) >= $amount) {
                // Dana Wants tidak cukup, coba ambil dari Savings
                $canAfford = true;
                $deductedFromCategory = 'Savings';
            }
        }

        if ($canAfford) {
            // Jika dana cukup, buat transaksi
            $transaction = $user->transactions()->create([
                'amount' => $amount,
                'type' => 'expense',
                'category' => $deductedFromCategory, // Catat pengeluaran dari kategori yang benar
                'note' => $validated['note'] ?? null,
            ]);
            return response()->json($transaction, 201);
        } else {
            // Jika dana sama sekali tidak cukup
            return response()->json([
                'message' => 'Transaksi Gagal. Dana Anda tidak mencukupi bahkan setelah mengambil dari tabungan.'
            ], 422); // 422: Unprocessable Entity
        }
    }
}