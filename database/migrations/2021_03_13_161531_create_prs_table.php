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
        Schema::create('prs', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('type');
            $table->string('detail');
            $table->dateTime('dt');
            $table->date('acc_date')->nullable();

            $table->string('bill_id')->nullable();
            $table->decimal('pay')->default(0);
            $table->decimal('receive')->default(0);

            $table->foreignId('team_id');
            $table->string('emp_name')->nullable();

            $table->dateTime('cancel_on')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->string('cancel_emp_name')->nullable();

            $table->foreignId('prable_id')->nullable();;
            $table->string('prable_type')->nullable();;

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
        Schema::dropIfExists('prs');
    }
}
