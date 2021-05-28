<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntRangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intr_range_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pawn_config_id');
            $table->decimal('min',10,2);
            $table->decimal('max',10,2);
            $table->decimal('rate', 3,2);
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
        Schema::dropIfExists('int_range_rates');
    }
}
