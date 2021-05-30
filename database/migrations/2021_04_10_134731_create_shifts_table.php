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
            $table->date('d')->nullable();
            $table->decimal('cash_begin')->default(0);
            $table->decimal('cash_in')->default(0);
            $table->decimal('cash_out')->default(0);
            $table->decimal('cash_count')->default(0);
            $table->decimal('cash_to_safe')->default(0);
            $table->decimal('cash_to_bank')->default(0);
            $table->decimal('cash_end')->default(0);
            $table->decimal('bank_transfer_in')->default(0);
            $table->decimal('bank_transfer_out')->default(0);
            $table->decimal('bank_transfer_end')->default(0);
            $table->decimal('card')->default(0);
            $table->foreignId('open_user_id');
            $table->foreignId('close_user_id')->nullable();
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->enum('status',['open','close']);
            $table->text('note')->nullable();
            $table->decimal('pawn_amount',10)->default(0);
            $table->unsignedInteger('pawn_count')->default(0);
            $table->decimal('saving_amount', 10)->default(0);
            $table->unsignedInteger('saving_count')->default(0);
            $table->decimal('old_gold_96')->default(0);
            $table->decimal('old_gold_90')->default(0);
            $table->decimal('old_gold_99')->default(0);
            $table->decimal('real_old_gold_96')->default(0);
            $table->decimal('real_old_gold_90')->default(0);
            $table->decimal('real_old_gold_99')->default(0);

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
