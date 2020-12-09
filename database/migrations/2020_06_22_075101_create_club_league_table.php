<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClubLeagueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club_league', function (Blueprint $table) {
            $table->unsignedInteger('league_id');
            $table->foreign('league_id')->references('id')->on('leagues');
            $table->unsignedInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs');
            $table->char('league_char');
            $table->smallInteger('league_no');
            $table->timestamps();
            $table->unique(['league_id','league_char']);
            $table->unique(['league_id','league_no']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('club_league');
    }
}
