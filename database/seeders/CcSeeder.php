<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CcSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BankAccountSeeder::class);
    }
}
