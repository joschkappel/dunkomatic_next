<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_downloads', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('report_id');
            $table->unsignedBigInteger('user_id')->nullable;
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions');
            $table->unsignedInteger('club_id')->nullable();
            $table->foreign('club_id')->references('id')->on('clubs');
            $table->unsignedInteger('league_id')->nullable();
            $table->foreign('league_id')->references('id')->on('leagues');
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
        Schema::dropIfExists('report_downloads');
    }
};
