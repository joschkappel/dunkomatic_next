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
        Schema::create('report_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('report_id');
            $table->string('report_class');
        });

        Artisan::call('db:seed', [
            '--class' => 'ReportClassesSeeder',
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
        Schema::dropIfExists('report_classes');
    }
};
