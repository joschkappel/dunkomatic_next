<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('league_id');
            $table->foreign('league_id')->references('id')->on('leagues');
            $table->string('region',5);
            $table->foreign('region')->references('id')->on('regions');
            $table->smallInteger('game_no');
            $table->date('game_plandate');
            $table->date('game_date');
            $table->time('game_time');
            $table->unsignedInteger('club_id_home');
            $table->foreign('club_id_home')->references('id')->on('clubs');
            $table->unsignedInteger('team_id_home');
            $table->foreign('team_id_home')->references('id')->on('teams');
            $table->string('team_home',5);
            $table->string('team_char_home',2);
            $table->unsignedInteger('club_id_guest');
            $table->foreign('club_id_guest')->references('id')->on('clubs');
            $table->unsignedInteger('team_id_guest');
            $table->foreign('team_id_guest')->references('id')->on('teams');
            $table->string('team_guest',5);
            $table->string('team_char_guest',2);
            $table->string('gym_no',2);
            $table->string('referee_1',4);
            $table->string('referee_2',4);
            $table->timestamps();
            $table->index('league_id');
            $table->index('club_id_home');
            $table->index('club_id_guest');
            $table->index('team_id_home');
            $table->index('team_id_guest');
            $table->index('region');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
