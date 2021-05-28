<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOldGoldStockCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_gold_stock_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gold_percent_id');
            $table->foreignId('team_id');
            $table->decimal('avg_per_baht')->default(0);
            $table->decimal('qty_begin')->nullable();
            $table->decimal('qty_in')->nullable();
            $table->decimal('qty_out')->nullable();
            $table->decimal('qty_end')->nullable();
            $table->decimal('wt_begin')->nullable();
            $table->decimal('wt_in')->nullable();
            $table->decimal('wt_out')->nullable();
            $table->decimal('wt_end')->nullable();
            $table->string('description')->nullable();
            $table->dateTime('dt')->nullable();
            $table->foreignId('ref_id');
            $table->string('ref_type');
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
        Schema::dropIfExists('old_gold_stock_cards');
    }
}
