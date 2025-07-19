<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Memperbarui nama pengguna yang terotentikasi.
     */
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return response()->json([
            'message' => 'Nama berhasil diperbarui',
            'user' => $user,
        ]);
    }

    /**
     * Memperbarui foto profil pengguna yang terotentikasi.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        // Hapus foto lama jika ada
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Simpan foto baru dan dapatkan path-nya
        $path = $request->file('photo')->store('avatars', 'public');

        // Simpan path ke database
        $user->profile_photo_path = $path;
        $user->save();

        return response()->json([
            'message' => 'Foto profil berhasil diunggah',
            'path' => $path,
            'url' => $user->profile_photo_url // Menggunakan accessor
        ]);
    }
}
