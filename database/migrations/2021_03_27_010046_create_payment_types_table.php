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
            $table->enum('type', ['pay', 'receive']);
            $table->boolean('system');
            $table->boolean('refable')->default(false);
        });

        \App\Models\PaymentType::insert([
            ['id' => 'paw', 'name' => 'รับขายฝาก', 'type' => 'pay', 'system' => true, 'refable' => true],
            ['id' => 'int', 'name' => 'ต่อดอก', 'type' => 'receive', 'system' => true, 'refable' => true],
            ['id' => 'mor', 'name' => 'เพิ่มต้น', 'type' => 'pay', 'system' => true, 'refable' => true],
            ['id' => 'les', 'name' => 'ตัดต้น', 'type' => 'receive', 'system' => true, 'refable' => true],
            ['id' => 'red', 'name' => 'ไถ่ถอน', 'type' => 'receive', 'system' => true, 'refable' => true],
            ['id' => 'stock-import', 'name' => 'นำเข้าสินค้า', 'type' => 'pay', 'system' => true, 'refable' => true],
            ['id' => 'sale', 'name' => 'ขาย', 'type' => 'receive', 'system' => true, 'refable' => true],
            ['id' => 'buy', 'name' => 'ซื้อ', 'type' => 'pay', 'system' => true, 'refable' => true],
            ['id' => 'change', 'name' => 'เปลี่ยน', 'type' => 'receive', 'system' => true, 'refable' => true],
            ['id' => 'saving-dep', 'name' => 'ฝากออม', 'type' => 'receive', 'system' => true, 'refable' => true],
            ['id' => 'saving-refund', 'name' => 'คืนเงินออม', 'type' => 'pay', 'system' => true, 'refable' => true],
            ['id' => 'cash-to-safe', 'name' => 'ส่งเงินเซฟ', 'type' => 'pay', 'system' => true, 'refable' => false],
            ['id' => 'cash-from-safe', 'name' => 'เบิกเงินเซฟ', 'type' => 'receive', 'system' => true, 'refable' => false],
            ['id' => 'cash-to-bank', 'name' => 'ส่งเข้าแบงค์', 'type' => 'pay', 'system' => true, 'refable' => false],
            ['id' => 'cash-from-bank', 'name' => 'เบิกจากแบงค์', 'type' => 'receive', 'system' => true, 'refable' => false],
            ['id' => 'cash-in', 'name' => 'เงินสด เพิ่ม', 'type' => 'receive', 'system' => true, 'refable' => false],
            ['id' => 'cash-out', 'name' => 'เงินสด ลด', 'type' => 'pay', 'system' => true, 'refable' => false],
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
