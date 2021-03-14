<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignId('team_id');
            $table->string('emp_name')->nullable();
            $table->date('acc_date')->nullable();
            $table->string('payment_no');
            $table->dateTime('dt');

            $table->string('detail');
            $table->string('bill_id')->nullable();
            $table->decimal('pay')->default(0);
            $table->decimal('receive')->default(0);

            $table->string('method');
            $table->string('transfer_bank')->nullable();
            $table->string('transfer_acc_no')->nullable();
            $table->string('creditcard_bank')->nullable();
            $table->string('creditcard_bank_no')->nullable();
            $table->decimal('creditcard_percent_fee')->nullable();

            $table->dateTime('cancel_on')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->string('cancel_emp_name')->nullable();

            $table->foreignId('paymentable_id')->nullable();;
            $table->string('paymentable_type')->nullable();;

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
        Schema::dropIfExists('payments');
    }
}
