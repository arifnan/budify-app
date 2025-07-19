<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class CheckUserStreaks extends Command
{
    protected $signature = 'streaks:check';
    protected $description = 'Check and update user streaks daily.';

    public function handle()
    {
        $this->info('Checking user streaks...');
        $users = User::all();

        foreach ($users as $user) {
            $today = Carbon::today();
            // Jika user belum pernah dicek, anggap tanggalnya kemarin
            $lastCheck = $user->last_streak_check ? Carbon::parse($user->last_streak_check) : $today->copy()->subDay();

            // Hanya proses jika pengecekan terakhir adalah kemarin, untuk memastikan pengecekan harian
            if ($lastCheck->isYesterday()) {
                // Hitung sisa saldo savings
                $savingsBalance = $this->calculateSavingsBalance($user);

                if ($savingsBalance > 0) {
                    // Jika saldo aman, lanjutkan streak
                    $user->streak_count++;
                } else {
                    // Jika saldo habis, reset streak
                    $user->streak_count = 0;
                    $this->info("Streak reset for user: {$user->email}");
                }

                $user->last_streak_check = $today;
                $user->save();
                $this->info("Processed streak for user: {$user->email}. Current streak: {$user->streak_count}");
            }
        }
        $this->info('Streak check finished.');
        return 0;
    }

    private function calculateSavingsBalance(User $user)
    {
        $cycleStartDate = $user->cycle_started_at ?? $user->created_at;

        // Total pemasukan dalam siklus
        $totalIncome = $user->transactions()->where('type', 'income')->where('category', '!=', 'Savings')->where('created_at', '>=', $cycleStartDate)->sum('amount');
        
        // Alokasi untuk savings dari pemasukan
        $savingsAllocation = $totalIncome * 0.30;
        
        // Pemasukan lain ke savings (misal dari sisa siklus)
        $otherSavingsIncome = $user->transactions()->where('type', 'income')->where('category', 'Savings')->where('created_at', '>=', $cycleStartDate)->sum('amount');
        
        // Pengeluaran dari savings
        $savingsExpenses = $user->transactions()->where('type', 'expense')->where('category', 'Savings')->where('created_at', '>=', '1970-01-01')->sum('amount');

        return ($savingsAllocation + $otherSavingsIncome) - $savingsExpenses;
    }
}
