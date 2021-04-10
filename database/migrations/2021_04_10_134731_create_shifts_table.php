<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id');
            $table->date('dt_start');
            $table->decimal('cash_begin');
            $table->decimal('cash');
            $table->decimal('cash_to_safe');
            $table->decimal('cash_to_bank');
            $table->decimal('cash_end');
            $table->decimal('bank');
            $table->decimal('card');
            $table->foreignId('open_user_id');
            $table->foreignId('close_user_id')->nullable();
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->enum('status',['open','close']);
            $table->text('note');
            $table->decimal('pawn_amount',10)->default(0);
            $table->unsignedInteger('pawn_count')->default(0);
            $table->decimal('saving_amount', 10)->default(0);
            $table->unsignedInteger('saving_count')->default(0);
            $table->double('old_gold_965')->default(0);
            $table->double('old_gold_90')->default(0);
            $table->double('old_gold_99')->default(0);

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
        Schema::dropIfExists('shifts');
    }
}
