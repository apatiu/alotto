<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('savings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code');
            $table->foreignId('customer_id');
            $table->foreignId('team_id');
            $table->decimal('gold_price_sale');
            $table->dateTime('dt');
            $table->dateTime('dt_due')->nullable();
            $table->dateTime('dt_close')->nullable();
            $table->decimal('price_total')->nullable();
            $table->decimal('price_pay')->nullable();
            $table->decimal('price_remain')->nullable();
            $table->string('note')->nullable();
            $table->decimal('items_wt')->nullable();
            $table->decimal('items_qty')->nullable();
            $table->enum('status',['open','close','cancel']);
            $table->foreignId('user_id');
            $table->integer('life')->nullable();
            $table->decimal('price_refund')->nullable();
            $table->decimal('price_forward')->nullable();
            $table->foreignId('prev_id')->nullable();
            $table->foreignId('next_id')->nullable();
            $table->decimal('saving_wt')->nullable();
        });

        Schema::create('saving_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('saving_id');
            $table->foreignId('product_id');
            $table->string('product_name');
            $table->string('product_wt');
            $table->decimal('qty');
            $table->decimal('wt');
            $table->decimal( 'price');
            $table->decimal('price_total');
            $table->foreignId('sale_id')->nullable();
        });

        Schema::create('saving_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('saving_id');
            $table->decimal('amount');
            $table->dateTime('dt');
            $table->string('note')->nullable();
            $table->decimal('wt')->nullable();
            $table->decimal('gold_price_sale');
            $table->boolean('is_forward')->default(false)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('savings');
        Schema::dropIfExists('saving_details');
    }
}
