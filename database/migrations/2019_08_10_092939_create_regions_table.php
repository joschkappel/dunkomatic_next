<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\JobFrequencyType;
use App\Enums\ReportFileType;

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
            $table->unsignedInteger('job_game_overlaps')->default(JobFrequencyType::never);
            $table->unsignedInteger('game_slot')->default(120);
            $table->unsignedInteger('job_game_notime')->default(JobFrequencyType::never);
            $table->unsignedInteger('job_noleads')->default(JobFrequencyType::never);
            $table->unsignedInteger('job_email_valid')->default(JobFrequencyType::never);
            $table->unsignedInteger('job_league_reports')->default(JobFrequencyType::never);
            $table->unsignedInteger('fmt_league_reports')->default(ReportFileType::HTML);
            $table->unsignedInteger('job_club_reports')->default(JobFrequencyType::never);
            $table->unsignedInteger('fmt_club_reports')->default(ReportFileType::HTML);
            $table->unsignedInteger('job_exports')->default(JobFrequencyType::never);
        });
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
