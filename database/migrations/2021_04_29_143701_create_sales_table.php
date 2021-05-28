<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->dateTime('dt')->nullable();
            $table->decimal('gold_price_sale')->nullable();
            $table->decimal('gold_price_buy')->nullable();
            $table->decimal('gold_price_tax')->nullable();

            $table->foreignId('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_tax_id')->nullable();


            $table->decimal('total_price_sale')->nullable();
            $table->decimal('total_price_buy')->nullable();
            $table->decimal('total_amount')->nullable();

            $table->decimal('total_wt_sale')->nullable();
            $table->decimal('total_wt_buy')->nullable();
            $table->decimal('total_qty_sale')->nullable();
            $table->decimal('product_cost_price')->nullable();
            $table->decimal('total_deposit')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->foreignId('team_id');
            $table->text('note')->nullable();
            $table->enum('type', ['sale', 'buy', 'change']);
            $table->enum('status', ['open', 'hold', 'checked', 'cancel']);
            $table->foreignId('user_id_cancel')->nullable();

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
        Schema::dropIfExists('sales');
    }
}
