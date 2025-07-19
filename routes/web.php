<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-admin-password', function () {
    // 1. Cari user berdasarkan email
    $user = User::where('email', 'admin@gmail.com')->first();

    // Jika user tidak ditemukan, tampilkan pesan
    if (!$user) {
        return "Pengguna dengan email admin@gmail.com tidak ditemukan.";
    }

    // 2. Atur password baru Anda di sini
    $newPassword = 'admin';
    $user->password = Hash::make($newPassword);

    // 3. Simpan perubahan
    $user->save();

    return "Password untuk " . $user->email . " telah berhasil diubah menjadi: " . $newPassword;
});