<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => ['required', Rule::in(['income', 'expense'])],
            'category_id' => 'required_if:type,expense|nullable|exists:categories,id',
            'note' => 'nullable|string',
        ]);

        $user = Auth::user();
        $amount = (float) $validated['amount'];

        if ($validated['type'] === 'income') {
            // Alokasikan dana ke saldo user
            Transaction::allocateIncome($user, $amount);

            // Buat catatan transaksi untuk pemasukan
            $user->transactions()->create([
                'amount' => $amount,
                'type' => 'income',
                'note' => $validated['note'] ?? 'Pemasukan',
            ]);

            return response()->json([
                'message' => 'Pemasukan berhasil ditambahkan dan dialokasikan.',
            ], 201);
        }

        if ($validated['type'] === 'expense') {
            // Lakukan pengurangan dana dari saldo user
            $success = Transaction::deductExpense($user, $amount, $validated['category_id']);

            if ($success) {
                // Jika pengurangan berhasil, baru catat transaksi pengeluarannya
                $user->transactions()->create($validated);
                return response()->json([
                    'message' => 'Pengeluaran berhasil dicatat.',
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Transaksi Gagal. Dana Anda tidak mencukupi.',
                ], 422);
            }
        }

        return response()->json([
            'message' => 'Jenis transaksi tidak valid.',
        ], 400);
    }
}
