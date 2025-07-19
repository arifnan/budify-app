<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Mengambil semua kategori dan mengelompokkannya berdasarkan tipe
        $categories = Category::all()->groupBy('type');
        
        return response()->json($categories);
    }
}
