<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotteries', function (Blueprint $table) {
            $table->id();
            $table->datetime('dt_buy');
            $table->datetime('dt_sale')->nullable();
            $table->string('number');
            $table->unsignedInteger('qty');
            $table->foreignId('prize_id');
            $table->decimal('prize_amount');
            $table->decimal('price_buy');
            $table->decimal('price_sale');
            $table->foreignId('user_id');
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
        Schema::dropIfExists('lotteries');
    }
}
