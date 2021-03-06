<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePawnIntReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pawn_int_receives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pawn_id');
            $table->date('dt');
            $table->date('dt_end')->nullable();
            $table->decimal('amount');
            $table->unsignedInteger('month_pay')->nullable();
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
        Schema::dropIfExists('pawn_int_receives');
    }
}
