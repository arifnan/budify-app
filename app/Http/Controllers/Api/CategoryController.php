<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // <-- Tambahkan ini untuk logging

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar semua kategori, dikelompokkan berdasarkan tipe.
     */
    public function index()
    {
        try {
            $categories = Category::all();

            if ($categories->isEmpty()) {
                // Jika tidak ada kategori sama sekali di database
                return response()->json([
                    'Needs' => [],
                    'Wants' => [],
                    'Savings' => [],
                ]);
            }

            $grouped = $categories->groupBy('type');
            
            $response = [
                'Needs' => $grouped->get('Needs', []),
                'Wants' => $grouped->get('Wants', []),
                'Savings' => $grouped->get('Savings', []),
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            // Jika terjadi error, catat di log server untuk debugging
            Log::error('Error fetching categories: ' . $e->getMessage());

            // Kirim respons error yang jelas ke aplikasi
            return response()->json([
                'message' => 'Terjadi kesalahan pada server saat mengambil kategori.'
            ], 500);
        }
    }
}
