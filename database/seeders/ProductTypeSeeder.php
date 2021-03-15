<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_types')->truncate();
        ProductType::insert([
            ['name' => 'คอ', 'id' => 'N'],
            ['name' => 'มือ', 'id' => 'B'],
            ['name' => 'จี้', 'id' => 'P'],
            ['name' => 'แหวน', 'id' => 'R'],
            ['name' => 'พระ', 'id' => 'A'],
            ['name' => 'ทองแท่ง', 'id' => 'BAR'],
        ]);
    }
}
