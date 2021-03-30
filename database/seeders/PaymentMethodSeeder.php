<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::insert([
            ['id' => 'cash', 'name'=> 'เงินสด'],
            ['id' => 'bank', 'name'=> 'โอนเงิน'],
            ['id' => 'card', 'name'=> 'บัตรเครดิต'],
//            ['code' => 'point', 'name'=> 'แต้ม'],

        ]);
    }
}
