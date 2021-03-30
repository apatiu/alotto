<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BankAccount::insert([
            ['name' => 'BBL Aor', 'bank' => 'BBL', 'acc_no' => '646 0125 458'],
            ['name' => 'BBL A', 'bank' => 'BBL', 'acc_no' => '646 0035 055'],
        ]);
    }
}
