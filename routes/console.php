<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Menjadwalkan command 'budgets:reset' untuk berjalan setiap hari.
Schedule::command('budgets:reset')->dailyAt('01:00');

// Tambahkan jadwal baru untuk pengecekan streak
Schedule::command('streaks:check')->dailyAt('02:00'); // Dijalankan setelah reset budget

Schedule::command('reminders:send-daily')->dailyAt('16:00');