<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Web\AdminLoginController;

Route::get('/', function () {
    return view('welcome');
});

