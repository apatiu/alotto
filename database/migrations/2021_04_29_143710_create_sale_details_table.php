<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id');
            $table->enum('status',['sale','buy']);
            $table->foreignId('product_id')->nullable();
            $table->decimal('product_percent')->nullable();
            $table->string('product_type')->nullable();
            $table->string('product_design')->nullable();
            $table->string('product_size')->nullable();
            $table->string('product_name')->nullable();
            $table->decimal('product_wt')->nullable();
            $table->decimal('cost_wage')->nullable();
            $table->decimal('tag_wage')->nullable();
            $table->decimal('cost_price')->nullable();
            $table->decimal('tag_price')->nullable();
            $table->decimal('avg_cost_per_baht')->nullable();
            $table->decimal('qty')->nullable();
            $table->decimal('wt')->nullable();
            $table->decimal('price_sale_total')->nullable();
            $table->decimal('price_sale_gold')->nullable();
            $table->decimal('price_sale_wage')->nullable();
            $table->decimal('price_buy_total')->nullable();
            $table->decimal('price_buy_calc')->nullable();
            $table->decimal('sale_by_baht')->nullable();
            $table->decimal('wage_by_pcs')->nullable();

            $table->decimal('profit_wage')->nullable();
            $table->decimal('profit_gold')->nullable();
            $table->decimal('profit_total')->nullable();

            $table->integer('change_id')->nullable();
            $table->decimal('change_price')->nullable();

            $table->decimal('deposit')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('real_cost_sale')->nullable();
            $table->decimal('real_cost_buy')->nullable();
            $table->decimal('real_profit_sale')->nullable();
            $table->decimal('real_pro_buy')->nullable();
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
        Schema::dropIfExists('sale_details');
    }
}
