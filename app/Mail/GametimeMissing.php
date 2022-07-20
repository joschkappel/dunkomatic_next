<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GametimeMissing extends Mailable
{
    use Queueable, SerializesModels;

    protected int $days_to_go;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(int $days_to_go=0)
    {
        $this->days_to_go = $days_to_go;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.gametime_missing_mail')
                    ->subject('pls enter game tmies')
                    ->with(['days_to_go' => $this->days_to_go]);
    }
}
