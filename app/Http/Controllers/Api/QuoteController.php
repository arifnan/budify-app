<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quote;

class QuoteController extends Controller
    {
        public function getRandomQuote()
        {
            $quote = Quote::inRandomOrder()->first();

            if (!$quote) {
                return response()->json([
                    'text' => 'Teruslah menabung, karena setiap langkah kecil membawamu lebih dekat pada mimpimu.',
                    'author' => 'Budify App'
                ], 200); // Fallback jika DB kosong
            }

            return response()->json($quote);
        }
    }
