<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteryPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_prizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('prize_amount');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('lottery_prizes')->insert([
           ['name'=>'รางวัลที่ 2', 'prize_amount'=>200000],
           ['name'=>'รางวัลที่ 3', 'prize_amount'=>80000],
           ['name'=>'รางวัลที่ 4', 'prize_amount'=>40000],
           ['name'=>'รางวัลที่ 5', 'prize_amount'=>20000],
           ['name'=>'เลขหน้า 3 ตัว', 'prize_amount'=>4000],
           ['name'=>'เลขท้าย 3 ตัว', 'prize_amount'=>4000],
           ['name'=>'เลขท้าย 2 ตัว', 'prize_amount'=>2000],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lottery_prizes');
    }
}
