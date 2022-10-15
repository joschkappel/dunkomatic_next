<?php

use App\Enums\JobFrequencyType;
use App\Enums\ReportFileType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 5)->unique();
            $table->string('name');
            $table->string('hq')->nullable();
            $table->timestamps();
            $table->unsignedInteger('job_game_overlaps')->default(JobFrequencyType::weekly);
            $table->unsignedInteger('game_slot')->default(120);
            $table->unsignedInteger('job_game_notime')->default(JobFrequencyType::weekly);
            $table->unsignedInteger('job_noleads')->default(JobFrequencyType::weekly);
            $table->unsignedInteger('job_email_valid')->default(JobFrequencyType::weekly);
            $table->unsignedInteger('job_league_reports')->default(JobFrequencyType::weekly);
            $table->unsignedInteger('fmt_league_reports')->default(ReportFileType::HTML);
            $table->unsignedInteger('job_club_reports')->default(JobFrequencyType::weekly);
            $table->unsignedInteger('fmt_club_reports')->default(ReportFileType::HTML);
            $table->unsignedInteger('job_exports')->default(JobFrequencyType::weekly);
            $table->date('close_assignment_at')->nullable();
            $table->date('close_registration_at')->nullable();
            $table->date('close_selection_at')->nullable();
            $table->date('close_scheduling_at')->nullable();
            $table->date('close_referees_at')->nullable();
            $table->boolean('auto_state_change')->default(false);
        });

        Artisan::call('db:seed', [
            '--class' => 'RegionsSeeder',
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
        Schema::dropIfExists('regions');
    }
}
