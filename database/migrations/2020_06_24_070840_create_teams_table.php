<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('team_no');
            $table->unsignedInteger('league_id')->nullable();
            $table->foreign('league_id')->references('id')->on('leagues');
            $table->unsignedInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs');
            $table->char('league_char')->nullable();
            $table->smallInteger('league_no')->nullable();
            $table->char('preferred_league_char')->nullable();
            $table->smallInteger('preferred_league_no')->nullable();
            $table->char('league_prev')->nullable();
            $table->smallInteger('training_day')->nullable();
            $table->time('training_time')->nullable();
            $table->smallInteger('preferred_game_day')->nullable();
            $table->time('preferred_game_time')->nullable();
            $table->string('shirt_color')->nullable();
            $table->string('coach_name')->nullable();
            $table->string('coach_phone1')->nullable();
            $table->string('coach_phone2')->nullable();
            $table->string('coach_email')->nullable();
            $table->boolean('changeable')->default(True);
            $table->timestamps();
            //            $table->unique(['club_id','team_no']);
            //$table->unique(['league_id','league_no']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
