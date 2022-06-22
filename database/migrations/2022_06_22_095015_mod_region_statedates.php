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
            $table->dropColumn(['close_assignment_at', 'close_registration_at']);
            $table->date('open_selection_at')->nullable();
            $table->date('open_scheduling_at')->nullable();
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
            $table->dropColumn(['open_selection_at', 'open_scheduling_at']);
            $table->date('close_assignment_at')->nullable();
            $table->date('close_registration_at')->nullable();
        });
    }
};
