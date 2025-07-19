<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
     * Menyimpan transaksi baru.
     */
    public function store(Request $request)
    {
        // Validasi input dari aplikasi mobile
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:255',
            'note' => 'nullable|string',
        ]);

        // Buat transaksi baru yang otomatis terhubung dengan user yang login
        $transaction = $request->user()->transactions()->create($validated);

        // Kembalikan response sukses beserta data transaksi yang baru dibuat
        return response()->json([
            'message' => 'Transaksi berhasil disimpan!',
            'transaction' => $transaction
        ], 201); // 201 artinya "Created"
    }
}