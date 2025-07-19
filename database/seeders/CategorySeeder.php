<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel dulu
        DB::table('categories')->truncate();

        $categories = [
            // NEEDS
            ['name' => 'Kos/Tempat Tinggal', 'type' => 'Needs'],
            ['name' => 'Konsumsi/Makan', 'type' => 'Needs'],
            ['name' => 'Transportasi', 'type' => 'Needs'],
            ['name' => 'Kebutuhan Akademik', 'type' => 'Needs'],
            ['name' => 'Paket Data/Pulsa', 'type' => 'Needs'],
            // WANTS
            ['name' => 'Nongkrong/Jajan', 'type' => 'Wants'],
            ['name' => 'Hiburan', 'type' => 'Wants'],
            ['name' => 'Hobi', 'type' => 'Wants'],
            ['name' => 'Pakaian & Skincare', 'type' => 'Wants'],
            ['name' => 'Langganan Digital', 'type' => 'Wants'],
            // SAVINGS
            ['name' => 'Dana Darurat', 'type' => 'Savings'],
            ['name' => 'Pendidikan', 'type' => 'Savings'],
            ['name' => 'Tujuan Finansial', 'type' => 'Savings'],
            ['name' => 'Kesehatan', 'type' => 'Savings'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
