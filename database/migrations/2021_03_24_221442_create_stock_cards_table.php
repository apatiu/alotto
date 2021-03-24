<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_cards', function (Blueprint $table) {
            $table->id();
            $table->string('product_id');
            $table->decimal('cost_wage')->nullable();
            $table->decimal('tag_wage')->nullable();
            $table->decimal('cost_price')->nullable();
            $table->decimal('tag_price')->nullable();
            $table->decimal('qty_begin')->nullable();
            $table->decimal('qty_in')->nullable();
            $table->decimal('qty_out')->nullable();
            $table->decimal('qty_end')->nullable();
            $table->decimal('weight_begin')->nullable();
            $table->decimal('weight_in')->nullable();
            $table->decimal('weight_out')->nullable();
            $table->decimal('weight_end')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('dt')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_cards');
    }
}
