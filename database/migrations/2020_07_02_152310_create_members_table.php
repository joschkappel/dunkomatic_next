<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname',20);
            $table->string('lastname', 60);
            $table->string('city', 40);
            $table->string('zipcode', 10);
            $table->string('street', 40);
            $table->string('phone1', 40)->nullable();
            $table->string('phone2', 40)->nullable();
            $table->string('mobile', 40)->nullable();
            $table->string('email1', 40);
            $table->string('email2', 40)->nullable();
            $table->string('fax1', 40)->nullable();
            $table->string('fax2', 40)->nullable();
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
        Schema::dropIfExists('members');
    }
}
