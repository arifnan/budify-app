<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Memperbarui siklus anggaran pengguna.
     */
    public function updateBudgetCycle(Request $request)
    {
        $request->validate([
            'cycle' => ['required', 'string', Rule::in(['weekly', 'monthly'])],
        ]);

        $user = Auth::user();
        $user->budget_cycle = $request->cycle;
        $user->save();

        return response()->json([
            'message' => 'Siklus anggaran berhasil diperbarui ke ' . $request->cycle,
            'user' => $user,
        ]);
    }
}
