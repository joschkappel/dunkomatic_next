<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeagueSizeCharTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_size_chars', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('league_size_id');
            $table->foreign('league_size_id')->references('id')->on('league_sizes');
            $table->string('team_char',2);
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
        Schema::dropIfExists('league_size_chars');
    }
}
