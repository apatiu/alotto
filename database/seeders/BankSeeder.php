<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bank::insert([
            ['code' => 'BBL', 'name' => 'ธนาคารกรุงเทพ'],
            ['code' => 'SCB', 'name' => 'ธนาคารไทยพานิชย์'],
        ]);
    }
}
