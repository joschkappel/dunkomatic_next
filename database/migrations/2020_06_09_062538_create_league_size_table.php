<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeagueSizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_sizes', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('size');
            $table->unsignedInteger('iterations');
            $table->string('description',40);
            $table->unique(['size','iterations']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league_sizes');
    }
}
