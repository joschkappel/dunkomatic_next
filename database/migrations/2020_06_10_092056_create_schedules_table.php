<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('region_id');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->string('eventcolor')->default('green');
            $table->boolean('active')->default(True);
            $table->string('size');
            $table->foreign('size')->references('size')->on('league_team_sizes');
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
        Schema::dropIfExists('schedules');
    }
}
