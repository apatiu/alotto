<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_imports', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->date('d')->nullable();
            $table->foreignId('team_id');
            $table->string('supplier_id')->nullable();
            $table->string('emp_name')->nullable();
            $table->string('status')->nullable();
            $table->boolean('stock_updated')->nullable();
            $table->dateTime('stock_updated_on')->nullable();
            $table->text('note')->nullable();
            $table->decimal('product_weight_total');
            $table->decimal('tag_wage_total');
            $table->decimal('tag_price_total');
            $table->bigInteger('product_qty_total');
            $table->decimal('cost_wage_total');
            $table->decimal('cost_price_total');
            $table->decimal('bullion_cost');
            $table->decimal('real_weight_total');
            $table->decimal('real_cost');
            $table->timestamps();
        });

        Schema::create('stock_import_lines', function (Blueprint $table) {
            $table->id();
            $table->string('stock_import_id');
            $table->string('product_id');
            $table->string('gold_percent');
            $table->string('product_type_id');
            $table->string('product_design');
            $table->string('product_size');
            $table->string('product_name');
            $table->decimal('product_weight');
            $table->decimal('product_min');
            $table->decimal('cost_wage');
            $table->decimal('tag_wage');
            $table->decimal('cost_price');
            $table->decimal('tag_price');
            $table->boolean('sale_with_gold_price')->default(true);
            $table->decimal('product_qty');
            $table->decimal('product_weight_total');
            $table->decimal('avg_cost_per_baht');
            $table->text('descriptions');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public
    function down()
    {
        Schema::dropIfExists('stock_imports');
        Schema::dropIfExists('stock_import_lines');
    }
}
