<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeagueSizeSchemeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_size_schemes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('league_size_id');
            $table->foreign('league_size_id')->references('id')->on('league_sizes');
            $table->smallInteger('game_day');
            $table->smallInteger('game_no');
            $table->string('team_home', 2);
            $table->string('team_guest', 2);
            $table->timestamps();
            $table->unique(['league_size_id', 'game_no']);
        });

        Artisan::call('db:seed', [
            '--class' => 'LeagueSizeSchemesSeeder',
            '--force' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league_size_schemes');
    }
}
