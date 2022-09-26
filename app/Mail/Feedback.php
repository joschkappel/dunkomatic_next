<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Feedback extends Mailable
{
    use Queueable, SerializesModels;

    protected User $user;

    protected string $title;

    protected string $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $title, string $body)
    {
        $this->user = $user;
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->user->email, $this->user->name)
                    ->markdown('mail.feedback_mail')
                    ->subject($this->title)
                    ->with(['body' => $this->body]);
    }
}
