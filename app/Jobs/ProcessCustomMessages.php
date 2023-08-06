<?php

namespace App\Jobs;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class ProcessCustomMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::notice('[JOB][SEND CUSTOM MESSAGES] check for messages to send.');

        // get all messages
        // that have not been sent
        // and are scheduled for today

        $messages = Message::whereNull('sent_at')->whereDate('send_at', now())->get();
        Log::notice('[JOB][SEND CUSTOM MESSAGES] found messages to send.', ['message_cnt' => count($messages)]);

        if (count($messages) > 0) {
            // now send the messages
            $msg_jobs = collect();
            foreach ($messages as $m) {
                $msg_jobs[] = new SendCustomMessage($m);
            }
            $batch = Bus::batch($msg_jobs)
                ->name('Send eMails')
                ->onConnection('redis')
                ->onQueue('mails')
                ->dispatch();
            Log::notice('[JOB][SEND CUSTOM MESSAGES] eMail batch job started.', ['job_cnt' => count($msg_jobs), 'batch' => $batch]);
        }
    }
}
