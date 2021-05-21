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
            $table->boolean('system');
        });

        \App\Models\PaymentType::insert([
            ['id' => 'paw', 'name' => 'รับขายฝาก', 'system' => true],
            ['id' => 'int', 'name' => 'ต่อดอก', 'system' => true],
            ['id' => 'mor', 'name' => 'เพิ่มเงิน', 'system' => true],
            ['id' => 'les', 'name' => 'ตัดต้น', 'system' => true],
            ['id' => 'red', 'name' => 'ไถ่ถอน', 'system' => true],
            ['id' => 'stock-import', 'name' => 'นำเข้าสินค้า', 'system' => true],
            ['id' => 'sale', 'name' => 'ขาย', 'system' => true],
            ['id' => 'buy', 'name' => 'ซื้อ', 'system' => true],
            ['id' => 'change', 'name' => 'เปลี่ยน', 'system' => true],
            ['id' => 'saving-dep', 'name' => 'ฝากออม', 'system' => true],
            ['id' => 'saving-refund', 'name' => 'คืนเงินออม', 'system' => true],
            ['id' => 'cash-to-safe', 'name' => 'ส่งเงินเซฟ', 'system' => true],
            ['id' => 'cash-from-safe', 'name' => 'เบิกเงินเซฟ', 'system' => true],
            ['id' => 'cash-to-bank', 'name' => 'ส่งเข้าแบงค์', 'system' => true],
            ['id' => 'cash-from-bank', 'name' => 'เบิกจากแบงค์', 'system' => true],
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
