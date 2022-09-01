<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class CreateLeagueSizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_sizes', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('size');
            $table->string('description',40);
            $table->unique(['size']);
        });
        Artisan::call('db:seed', [
            '--class' => 'LeagueSizesSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league_sizes');
    }
}
