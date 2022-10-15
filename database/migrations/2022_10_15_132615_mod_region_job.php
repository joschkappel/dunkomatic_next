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
            $table->dropColumn(['job_game_notime', 'job_game_overlaps']);
            $table->unsignedInteger('job_noleads')->default(JobFrequencyType::weekly)->change();
            $table->unsignedInteger('job_email_valid')->default(JobFrequencyType::weekly)->change();
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
            $table->unsignedInteger('job_game_overlaps')->default(JobFrequencyType::weekly);
            $table->unsignedInteger('job_game_notime')->default(JobFrequencyType::weekly);
        });
    }
};
