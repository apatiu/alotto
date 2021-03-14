<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->index();
            $table->foreignId('team_id');

            $table->decimal('gold_percent');
            $table->foreignId('product_group_id');
            $table->string('design')->nullable();
            $table->string('size')->nullable();
            $table->string('name');

            $table->decimal('min_qty')->nullable();
            $table->decimal('weight')->nullable();
            $table->boolean('weightbaht')->default(true);
            $table->boolean('pricebybullion')->default(true);

            $table->decimal('cost_wage')->nullable();
            $table->decimal('cost_price')->nullable();
            $table->decimal('tag_wage')->nullable();
            $table->decimal('tag_price')->nullable();
            $table->decimal('avg_cost_per_baht')->default(0);
            $table->boolean('sale_with_gold_price')->default(true);
            $table->boolean('wage_by_pcs')->default(false);

            $table->decimal('qty')->default(0);


            $table->string('photo_url')->nullable();

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
        Schema::dropIfExists('product_groups');
        Schema::dropIfExists('products');
    }
}
