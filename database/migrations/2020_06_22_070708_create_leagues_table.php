<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('region',5);
            $table->foreign('region')->references('code')->on('regions');
            $table->char('shortname', 10)->unique();
            $table->text('name');
            $table->boolean('active')->default(True);
            $table->boolean('changeable')->default(True);
            $table->boolean('above_region')->default(False);
            $table->unsignedInteger('schedule_id')->nullable();
            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->unsignedInteger('age_type')->nullable();
            $table->unsignedInteger('gender_type')->nullable();
            $table->timestamps();
            //$table->unique('region','shortname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leagues');
    }
}
