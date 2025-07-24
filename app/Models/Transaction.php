<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'type',
        'user_id',
        'category_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Static method to handle income allocation to budgets.
     *
     * @param \App\Models\User $user
     * @param float $amount
     */
    public static function allocateIncome($user, $amount)
    {
        $needsPortion = $amount * 0.4;
        $wantsPortion = $amount * 0.3;
        $savingsPortion = $amount * 0.3;

        // Tambahkan alokasi
        $user->needs += $needsPortion;
        $user->wants += $wantsPortion;
        $user->savings += $savingsPortion;
        $user->save();
    }

    /**
     * Static method to handle expense deduction from budgets.
     *
     * @param \App\Models\User $user
     * @param float $amount
     * @return bool Success or not
     */
    public static function deductExpense($user, $amount): bool
    {
        if ($amount <= $user->needs) {
            $user->needs -= $amount;
        } elseif ($amount <= ($user->needs + $user->savings)) {
            $remaining = $amount - $user->needs;
            $user->needs = 0;
            $user->savings -= $remaining;
        } else {
            return false; // dana tidak cukup
        }

        $user->save();
        return true;
    }

    /**
     * Check if user has a transaction today.
     */
    public static function hasTransactionToday($userId): bool
    {
        return self::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->exists();
    }
}
