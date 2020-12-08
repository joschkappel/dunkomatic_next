<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('region_id');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->char('shortname', 4);
            $table->text('name');
            $table->string('club_no');
            $table->string('url')->nullable();
            $table->boolean('active')->default(True);
            $table->timestamps();
            $table->unique(['region_id','club_no']);
            $table->unique(['region_id','shortname']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clubs');
    }
}
