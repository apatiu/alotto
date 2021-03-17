<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoldPercentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gold_percents', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->decimal('percent_sale');
            $table->decimal('add_sale');
            $table->decimal('percent_buy');
            $table->decimal('deduct_buy');
            $table->decimal('percent_deduct_total_buy');
            $table->decimal('percent_buy_cost');
            $table->decimal('deduct_buy_cost');
            $table->decimal('deduct_buy_gold');

            $table->timestamps();
        });

        \App\Models\GoldPercent::create([
           'id' => 75,
           'name' => '75',
           'percent_sale' => '80',
           'add_sale' => 0,
           'percent_buy' => 30,
           'deduct_buy' => 100,
           'percent_deduct_total_buy' => 0,
           'percent_buy_cost' => 0,
           'deduct_buy_cost' => 0,
           'deduct_buy_gold' => 0,
        ]);
        \App\Models\GoldPercent::create([
           'id' => 90,
           'name' => '90',
           'percent_sale' => '90',
           'add_sale' => 0,
           'percent_buy' => 80,
           'deduct_buy' => 100,
           'percent_deduct_total_buy' => 0,
           'percent_buy_cost' => 0,
           'deduct_buy_cost' => 0,
           'deduct_buy_gold' => 0,
        ]);
        \App\Models\GoldPercent::create([
           'id' => 96,
           'name' => '96.5',
           'percent_sale' => '100',
           'add_sale' => 0,
           'percent_buy' => 94,
           'deduct_buy' => 100,
           'percent_deduct_total_buy' => 0,
           'percent_buy_cost' => 0,
           'deduct_buy_cost' => 0,
           'deduct_buy_gold' => 0,
        ]);
        \App\Models\GoldPercent::create([
           'id' => 99,
           'name' => '99.9',
           'percent_sale' => '104',
           'add_sale' => 0,
           'percent_buy' => 94,
           'deduct_buy' => 100,
           'percent_deduct_total_buy' => 0,
           'percent_buy_cost' => 0,
           'deduct_buy_cost' => 0,
           'deduct_buy_gold' => 0,
        ]);
        \App\Models\GoldPercent::create([
           'id' => 0,
           'name' => 'สินค้าเบ็ดเตล็ด',
           'percent_sale' => '0',
           'add_sale' => 0,
           'percent_buy' => 0,
           'deduct_buy' => 0,
           'percent_deduct_total_buy' => 0,
           'percent_buy_cost' => 0,
           'deduct_buy_cost' => 0,
           'deduct_buy_gold' => 0,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gold_percents');
    }
}
