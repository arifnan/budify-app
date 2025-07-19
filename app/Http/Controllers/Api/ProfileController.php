<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Memperbarui nama pengguna.
     */
    public function updateName(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = $request->user();
        $user->name = $validated['name'];
        $user->save();

        return response()->json([
            'message' => 'Nama berhasil diperbarui!',
            'user' => $user,
        ]);
    }

    /**
     * Mengunggah dan memperbarui foto profil pengguna.
     */
    public function updatePhoto(Request $request)
    {
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar
        ]);

        $user = $request->user();

        // Simpan file baru ke folder 'profile-photos' di dalam storage
        $path = $validated['photo']->store('profile-photos', 'public');

        // Hapus foto lama jika ada untuk menghemat ruang
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Simpan path file baru ke database
        $user->profile_photo_path = $path;
        $user->save();
        
        // Dapatkan URL lengkap untuk file yang diunggah
        $photoUrl = Storage::disk('public')->url($path);

        return response()->json([
            'message' => 'Foto profil berhasil diunggah!',
            'photo_url' => $photoUrl,
        ]);
    }
}