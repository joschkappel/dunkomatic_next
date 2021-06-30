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
            $table->foreign('region')->references('code')->on('regions');
            $table->smallInteger('game_no');
            $table->date('game_plandate');
            $table->date('game_date');
            $table->time('game_time')->nullable();
            $table->unsignedInteger('club_id_home')->nullable();
            $table->foreign('club_id_home')->references('id')->on('clubs');
            $table->unsignedInteger('team_id_home')->nullable();
            $table->foreign('team_id_home')->references('id')->on('teams');
            $table->string('team_home',5)->nullable();
            $table->string('team_char_home',2);
            $table->unsignedInteger('club_id_guest')->nullable();
            $table->foreign('club_id_guest')->references('id')->on('clubs');
            $table->unsignedInteger('team_id_guest')->nullable();
            $table->foreign('team_id_guest')->references('id')->on('teams');
            $table->string('team_guest',5)->nullable();
            $table->string('team_char_guest',2);
            $table->string('gym_no',2)->nullable();
            $table->unsignedInteger('gym_id')->nullable();
            $table->foreign('gym_id')->references('id')->on('gyms');
            $table->string('referee_1',4)->nullable();
            $table->string('referee_2',4)->nullable();
            $table->timestamps();
            $table->index('league_id');
            $table->index('club_id_home');
            $table->index('club_id_guest');
            $table->index('team_id_home');
            $table->index('team_id_guest');
            $table->index('region');
            $table->unique(['league_id', 'game_no']);
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
