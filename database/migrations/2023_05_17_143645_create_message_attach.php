<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Message;
use App\Models\MessageAttachment;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create new table
        Schema::create('message_attachments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('message_id');
            $table->foreign('message_id')->references('id')->on('messages')->cascadeOnDelete();
            $table->string('filename')->nullable();
            $table->string('location')->nullable();
        });

        // migrate old entries
        foreach (Message::whereNotNull('attachment_filename')->get() as $m) {
            // copy file to new location
            if (Storage::exists($m->attachment_location)) {
                Storage::disk('public')->writeStream(
                    $m->attachment_location,
                    Storage::readStream($m->attachment_location)
                );
                Log::info('attachment copied');
                Storage::delete($m->attachment_location);

                // create new attachment entry
                $attachment = new MessageAttachment(['filename' => $m->attachment_filename, 'location' => $m->attachment_location]);
                $m->message_attachments()->save($attachment);
            }
        }

        // now drop old cols
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['attachment_filename', 'attachment_location']);
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
