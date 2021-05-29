<?php

namespace Database\Seeders;

use App\Actions\Fortify\CreateNewUser;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    private $creator;

    public function __construct(CreateNewUser $creator)
    {
        $this->creator = $creator;
    }


    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $role = Role::firstOrCreate(['name' => 'gm']);

        $details = [
            'name' => 'apatiu',
            'username' => 'apatiu',
            'password' => 'thamet',
            'password_confirmation' => 'thamet',
            'terms' => ''
        ];

        $user = $this->creator->create($details);
        $user->assignRole('gm');

        $cust_cash = Customer::updateOrCreate([
            'id' => 1
        ], [
            'name' => 'ลูกค้าเงินสด'
        ]);

        $this->call(ProductTypeSeeder::class);
        $this->call(BankSeeder::class);
        $this->call(PaymentMethodSeeder::class);
//        $this->call(IntRangeRateSeeder::class);
//        $this->call(IntDiscountRateSeeder::class);


//        $this->call(TestSeeder::class);
//        $this->call(CcSeeder::class);
    }
}
