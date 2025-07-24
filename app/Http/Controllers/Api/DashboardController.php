<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // Total income
        $totalIncome = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->sum('amount');

        // Total expense per category
        $needsSpent = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('category', 'Needs')
            ->sum('amount');

        $wantsSpent = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('category', 'Wants')
            ->sum('amount');

        $savingsSpent = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('category', 'Savings')
            ->sum('amount');

        // Alokasi default
        $needsAllocated = $totalIncome * 0.4;
        $wantsAllocated = $totalIncome * 0.3;
        $savingsAllocated = $totalIncome * 0.3;

        // Remaining per category
        $needsRemaining = max(0, $needsAllocated - $needsSpent);
        $wantsRemaining = max(0, $wantsAllocated - $wantsSpent);
        $savingsRemaining = max(0, $savingsAllocated - $savingsSpent);

        // Hitung streak
        $today = now()->toDateString();
        $hasTodayTransaction = Transaction::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->exists();

        if ($savingsRemaining <= 0 || !$hasTodayTransaction) {
            $user->streak = 0;
        } else {
            $user->streak += 1;
        }

        $user->save();

        return response()->json([
            'needs' => [
                'budget' => $needsAllocated,
                'spent' => $needsSpent,
                'remaining' => $needsRemaining,
            ],
            'wants' => [
                'budget' => $wantsAllocated,
                'spent' => $wantsSpent,
                'remaining' => $wantsRemaining,
            ],
            'savings' => [
                'budget' => $savingsAllocated,
                'spent' => $savingsSpent,
                'remaining' => $savingsRemaining,
            ],
            'total_income' => $totalIncome,
            'streak' => $user->streak,
        ]);
    }
}
