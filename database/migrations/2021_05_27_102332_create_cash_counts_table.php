<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_counts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('1000')->nullable();
            $table->unsignedInteger('500')->nullable();
            $table->unsignedInteger('100')->nullable();
            $table->unsignedInteger('10')->nullable();
            $table->unsignedInteger('5')->nullable();
            $table->unsignedInteger('2')->nullable();
            $table->unsignedInteger('1')->nullable();
            $table->unsignedInteger('05')->nullable();
            $table->unsignedInteger('025')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_counts');
    }
}
