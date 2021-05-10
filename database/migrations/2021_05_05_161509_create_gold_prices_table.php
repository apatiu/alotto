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
            $table->double('gold_price_sale');
            $table->double('gold_price_buy');
            $table->double('gold_price_diff');
            $table->double('gold_price_tax');
            $table->double('spot_bid')->nullable();
            $table->double('spot_ask')->nullable();
            $table->double('spot_diff')->nullable();
            $table->double('thb_bid')->nullable();
            $table->double('thb_ask')->nullable();
            $table->double('thb_diff')->nullable();
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
