<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'contact_name' => $this->faker->name,
            'addr' => $this->faker->address,
            'tel' => $this->faker->phoneNumber,
            'code' => $this->faker->randomAscii,
            'email' => $this->faker->unique()->safeEmail,
            'tax_id' => $this->faker->unique()->numerify('#############')

        ];
    }
}
