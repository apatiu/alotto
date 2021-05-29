<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('company_name')->nullable();
            $table->string('branch_number',5)->nullable();
            $table->string('addr')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->removeColumn('company_name');
            $table->removeColumn('branch_number');
            $table->removeColumn('addr');
            $table->removeColumn('tax_id');
            $table->removeColumn('phone');

        });
    }
}
