<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageDestinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_destinations', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('message_id');
            $table->foreign('message_id')->references('id')->on('messages');
            $table->unsignedInteger('type');  // to or cc
            $table->unsignedInteger('scope'); // user, club, league, admin
            $table->unsignedInteger('region_id');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->unsignedInteger('club_id')->nullable();
            $table->foreign('club_id')->references('id')->on('clubs');
            $table->unsignedInteger('league_id')->nullable();
            $table->foreign('league_id')->references('id')->on('leagues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_destinations');
    }
}
