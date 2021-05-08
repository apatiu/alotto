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
            $table->decimal('avg_per_bath')->default(0);
            $table->decimal('qty_begin');
            $table->double('qty_in');
            $table->double('qty_out');
            $table->double('qty_end');
            $table->double('wt_begin');
            $table->double('wt_in');
            $table->double('wt_out');
            $table->double('wt_end');
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
