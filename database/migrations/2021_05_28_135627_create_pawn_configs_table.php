<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePawnConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pawn_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id');
            $table->unsignedInteger('life')->default(3);
            $table->unsignedDouble('intr_rate')->default(3);
            $table->unsignedDouble('min_intr')->default(0);
            $table->boolean('intr_rate_by_price')->default(false);
            $table->boolean('intr_discount_by_day')->defult(false);
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
        Schema::dropIfExists('pawn_configs');
    }
}
