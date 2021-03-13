<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_contact_role', function (Blueprint $table) {
            $table->foreignId('contact_id');
            $table->foreignId('contact_role_id');
        });

        Schema::create('contact_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $role = \App\Models\ContactRole::create(['name' => 'customer']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_contact_role');
        Schema::dropIfExists('contact_roles');
    }
}
