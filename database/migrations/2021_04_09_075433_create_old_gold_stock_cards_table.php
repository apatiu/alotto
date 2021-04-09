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
            $table->string('gold_percent_id');
            $table->foreignId('team_id');
            $table->decimal('avg_per_bath')->default(0);
            $table->decimal('qty_begin');
            $table->decimal('qty_in');
            $table->decimal('qty_out');
            $table->decimal('qty_remain');
            $table->string('description');
            $table->dateTime('dt');
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
