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
        Schema::table('teams', function (Blueprint $table) {
            $table->datetime('registered_at')->nullable();
            $table->datetime('charpicked_at')->nullable();
            $table->datetime('charreleased_at')->nullable();
            $table->char('charreleased')->nullable();
            $table->datetime('withdrawn_at')->nullable();
            $table->char('withdrawn_from')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['registered_at','charpicked_at','withdrawn_at','charreleased_at','charreleased','withdrawn_from']);
        });

    }
};
