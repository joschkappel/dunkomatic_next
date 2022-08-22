<?php

use App\Enums\JobFrequencyType;

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
        Schema::table('regions', function (Blueprint $table) {
            $table->dropColumn(['job_club_reports', 'job_league_reports','job_exports']);
            $table->dropColumn(['job_club_reports_lastrun_at', 'job_club_reports_running','job_club_reports_lastrun_ok']);
            $table->dropColumn(['job_league_reports_lastrun_at', 'job_league_reports_running','job_league_reports_lastrun_ok']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->unsignedInteger('job_club_reports')->default(JobFrequencyType::never);
            $table->unsignedInteger('job_league_reports')->default(JobFrequencyType::never);
            $table->unsignedInteger('job_exports')->default(JobFrequencyType::never);
            $table->dateTime('job_club_reports_lastrun_at')->nullable();
            $table->dateTime('job_league_reports_lastrun_at')->nullable();
            $table->boolean('job_club_reports_running')->default(false);
            $table->boolean('job_league_reports_running')->default(false);
            $table->boolean('job_club_reports_lastrun_ok')->default(true);
            $table->boolean('job_league_reports_lastrun_ok')->default(true);
        });
    }
};
