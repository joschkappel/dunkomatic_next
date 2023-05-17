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
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['attachment_filename', 'attachment_location']);
        });

        Schema::create('message_attachments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('message_id');
            $table->foreign('message_id')->references('id')->on('messages')->cascadeOnDelete();
            $table->string('filename')->nullable();
            $table->string('location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_attachments');
        Schema::table('messages', function (Blueprint $table) {
            $table->string('attachment_filename')->nullable();
            $table->string('attachment_location')->nullable();
        });
    }
};
