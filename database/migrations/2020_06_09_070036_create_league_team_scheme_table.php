<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeagueTeamSchemeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_team_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('size',5);
            $table->foreign('size')->references('size')->on('league_team_sizes');
            $table->smallInteger('game_day');
            $table->smallInteger('game_no');
            $table->string('team_home',2);
            $table->string('team_guest',2);                        
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
        Schema::dropIfExists('league_team_schemes');
    }
}
