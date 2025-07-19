<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ResetUserBudgets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budgets:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset user budgets at the end of their cycle and move remaining funds to savings.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting budget reset process...');
        $users = User::all();

        foreach ($users as $user) {
            $now = Carbon::now();
            $cycleStartDate = $user->cycle_started_at ?? $user->created_at;

            $isCycleEnd = false;
            if ($user->budget_cycle === 'weekly' && $cycleStartDate->diffInWeeks($now) >= 1) {
                $isCycleEnd = true;
            } elseif ($user->budget_cycle === 'monthly' && $cycleStartDate->diffInMonths($now) >= 1) {
                $isCycleEnd = true;
            }

            if ($isCycleEnd) {
                $this->info("Processing user: {$user->email}");

                // Hitung total pemasukan selama siklus ini
                $totalIncome = $user->transactions()
                                    ->where('type', 'income')
                                    ->where('created_at', '>=', $cycleStartDate)
                                    ->sum('amount');
                
                // Hitung total pengeluaran untuk Needs & Wants
                $needsSpent = $user->transactions()->where('category', 'Needs')->where('created_at', '>=', $cycleStartDate)->sum('amount');
                $wantsSpent = $user->transactions()->where('category', 'Wants')->where('created_at', '>=', $cycleStartDate)->sum('amount');

                // Tentukan budget untuk Needs & Wants
                $needsBudget = $totalIncome * 0.40;
                $wantsBudget = $totalIncome * 0.30;
                
                // Hitung sisa saldo
                $remainingNeeds = $needsBudget - $needsSpent;
                $remainingWants = $wantsBudget - $wantsSpent;

                $totalRemainder = 0;
                if ($remainingNeeds > 0) $totalRemainder += $remainingNeeds;
                if ($remainingWants > 0) $totalRemainder += $remainingWants;

                if ($totalRemainder > 0) {
                    // Buat transaksi baru untuk memindahkan sisa saldo ke Savings
                    Transaction::create([
                        'user_id' => $user->id,
                        'amount' => $totalRemainder,
                        'type' => 'income', // Dianggap sebagai "pemasukan" untuk Savings
                        'category' => 'Savings',
                        'note' => 'Sisa saldo dari siklus sebelumnya.',
                    ]);
                    $this->info("Moved {$totalRemainder} to savings for user: {$user->email}");
                }

                // Perbarui tanggal mulai siklus ke hari ini
                $user->cycle_started_at = $now;
                $user->save();
            }
        }
        $this->info('Budget reset process finished.');
        return 0;
    }
}
