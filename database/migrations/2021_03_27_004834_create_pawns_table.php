<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePawnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pawns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id');
            $table->dateTime('dt');
            $table->dateTime('dt_end');

            $table->dateTime('dt_out')->nullable();

            $table->foreignId('customer_id');
            $table->decimal('price');

            $table->decimal('int_rate');
            $table->decimal('int_per_month');
            $table->decimal('int_paid')->default(0);

            $table->unsignedInteger('life');
            $table->enum('status',['new','int','chg','red','cut']);
            $table->foreignId('prev_id')->nullable();
            $table->foreignId('next_id')->nullable();
            $table->foreignId('user_id')->nullable();
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
        Schema::dropIfExists('pawns');
    }
}
