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
            $table->string('region',5);
            $table->foreign('region')->references('code')->on('regions');
            $table->char('shortname', 4);
            $table->text('name');
            $table->string('club_no');
            $table->string('url')->nullable();
            $table->boolean('active')->default(True);
            $table->timestamps();
            $table->unique(['region','club_no']);
            $table->unique(['region','shortname']);
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
