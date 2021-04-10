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
            $table->id();
            $table->string('code')->index();
            $table->dateTime('dt')->nullable();
            $table->foreignId('team_id');
            $table->string('supplier_id')->nullable();
            $table->string('emp_name')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('stock_updated_on')->nullable();
            $table->text('note')->nullable();
            $table->decimal('product_weight_total')->nullable();;
            $table->decimal('tag_wage_total')->nullable();;
            $table->decimal('tag_price_total')->nullable();;
            $table->bigInteger('product_qty_total')->nullable();;
            $table->decimal('cost_wage_total')->nullable();;
            $table->decimal('cost_price_total')->nullable();;
            $table->decimal('cost_gold_total',12,2)->nullable();
            $table->decimal('real_weight_total')->nullable();
            $table->decimal('real_cost',12,2)->default(0);
            $table->timestamps();
        });

        Schema::create('stock_import_lines', function (Blueprint $table) {
            $table->id();
            $table->string('stock_import_id');
            $table->string('product_id');
            $table->string('gold_percent');
            $table->string('product_type_id');
            $table->string('product_design_id')->nullable();
            $table->string('product_size')->nullable();
            $table->string('product_name');
            $table->decimal('product_weight',10,3)->nullable();
            $table->decimal('product_weightbaht')->nullable();
            $table->decimal('product_min')->nullable();
            $table->boolean('wage_by_pcs')->default(false);
            $table->decimal('cost_wage')->nullable();
            $table->decimal('tag_wage')->nullable();
            $table->decimal('cost_price')->nullable();
            $table->decimal('tag_price')->nullable();
            $table->boolean('sale_with_gold_price')->default(true);
            $table->decimal('product_qty');
            $table->decimal('product_weight_total')->nullable();
            $table->decimal('avg_cost_per_baht')->nullable();
            $table->text('descriptions')->nullable();
            $table->timestamps();


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
