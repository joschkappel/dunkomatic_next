<?php

use App\Enums\LeagueState;
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
            $table->unsignedInteger('region_id');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->char('shortname', 10)->unique();
            $table->text('name');
            $table->boolean('above_region')->default(false);
            $table->unsignedInteger('league_size_id')->nullable();
            $table->foreign('league_size_id')->references('id')->on('league_sizes');
            $table->unsignedInteger('schedule_id')->nullable();
            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->unsignedInteger('age_type')->nullable();
            $table->unsignedInteger('gender_type')->nullable();
            $table->unsignedInteger('state')->default(LeagueState::Setup());
            $table->timestamp('assignment_closed_at')->nullable();
            $table->timestamp('registration_closed_at')->nullable();
            $table->timestamp('selection_opened_at')->nullable();
            $table->timestamp('selection_closed_at')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('scheduling_closed_at')->nullable();
            $table->timestamp('referees_closed_at')->nullable();
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
