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
            $table->string('id');
            $table->foreignId('team_id');
            $table->foreignId('product_group_id');
            $table->decimal('gold_percent');

            $table->string('size')->nullable();
            $table->string('design')->nullable();
            $table->string('name');

            $table->decimal('min_qty')->nullable();
            $table->decimal('weight')->nullable();
            $table->boolean('weightbath')->default(true);
            $table->boolean('pricebybullion')->default(true);

            $table->decimal('cost')->nullable();
            $table->decimal('price')->nullable();

            $table->string('photo_url')->nullable();

            $table->timestamps();
            $table->primary('id');
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
