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
        Schema::table('regions', function (Blueprint $table) {
            $table->dateTime('job_club_reports_lastrun_at')->nullable();
            $table->dateTime('job_league_reports_lastrun_at')->nullable();
            $table->boolean('job_club_reports_running')->default(false);
            $table->boolean('job_league_reports_running')->default(false);
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
            $table->dropColumn([
                'job_club_reports_lastrun_at',
                'job_league_reports_lastrun_at',
                'job_club_reports_running',
                'job_league_reports_running'
            ]);
        });
    }
};
