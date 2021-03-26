<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_types', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        \App\Models\PaymentType::insert([
            ['id' => 'paw', 'name' => 'รับขายฝาก'],
            ['id' => 'int', 'name' => 'ต่อดอก'],
            ['id' => 'mor', 'name' => 'เพิ่มเงิน'],
            ['id' => 'les', 'name' => 'ตัดต้น'],
            ['id' => 'red', 'name' => 'ไถ่ถอน'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_types');
    }
}
