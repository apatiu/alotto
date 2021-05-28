<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoldPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gold_prices', function (Blueprint $table) {
            $table->id();
            $table->dateTime('dt');
            $table->decimal('gold_price_sale');
            $table->decimal('gold_price_buy');
            $table->decimal('gold_price_diff');
            $table->decimal('gold_price_tax');
            $table->decimal('spot_bid')->nullable();
            $table->decimal('spot_ask')->nullable();
            $table->decimal('spot_diff')->nullable();
            $table->decimal('thb_bid')->nullable();
            $table->decimal('thb_ask')->nullable();
            $table->decimal('thb_diff')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gold_prices');
    }
}
