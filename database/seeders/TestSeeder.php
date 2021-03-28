<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\IntRangeRate;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::factory()->count(20)->create();
        Customer::factory()->count(20)->create();

        IntRangeRate::insert([
            ['min' => 0, 'max' => 39999.99, 'rate' => 3],
            ['min' => 40000, 'max' => 1000000, 'rate' => 2],
        ]);

    }

}
