<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log; // <-- Pastikan import ini ada
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\CategoryController; 
use App\Http\Controllers\Api\ChartController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\CalendarController;

// ---> INI BAGIAN INVESTIGASI <---
// Mencatat semua request yang masuk ke file api.php
Log::info('API route file was hit. Request path: ' . request()->path());
Log::info('Request data:', request()->all());
// --------------------------------

// Rute Publik (tidak perlu login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rute Terproteksi (wajib login/mengirim token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
  
    
    Route::post('/profile/update-name', [ProfileController::class, 'updateName']);
    Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto']);
  
    Route::post('/settings/update-cycle', [SettingsController::class, 'updateBudgetCycle']);

    Route::get('/quotes/random', [QuoteController::class, 'getRandomQuote']);
    Route::get('/categories', [CategoryController::class, 'index']);

    Route::get('/chart-data/line', [ChartController::class, 'getExpenseTrend']);
    
    // Endpoint untuk Diagram Pie, khusus untuk hari ini
    Route::get('/chart-data/pie', [ChartController::class, 'getTodaysSpendingByCategory']);

      Route::post('/devices/register', [DeviceController::class, 'register']);

      Route::get('/calendar-data', [CalendarController::class, 'getMonthlySummary']);
});