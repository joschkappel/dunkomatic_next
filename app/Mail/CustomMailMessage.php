<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;
use App\Models\Message;

class CustomMailMessage extends Mailable
{
    use Queueable, SerializesModels;

    protected Message $message;
    protected string $sender_name;
    protected string $sender_email;

    /**
     * Create a new message instance.
     *
     * @param Message $message
     * @return void
     *
     */
    public function __construct(Message $message)
    {
      $this->message = $message;

      $user = $message->user;
      Log::info('[EMAIL] Sending from.',['user'=>$user->email]);
      $this->sender_name = $user->name;
      $this->sender_email = $user->email;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

      $iL = explode('<p>',$this->message['body']);
      return $this->markdown('mail.appaction_mail')
                  ->subject($this->message['title'])
                  ->with([
                        'level' => 'success',
                        'greeting' => $this->message['greeting'],
                        'introLines' => $iL,
                        'outroLines' => [],
                        'salutation' => $this->message['salutation'],
                      ])
                  ->from($this->sender_email, $this->sender_name)
                  ->replyTo($this->sender_email, $this->sender_name);

    }
}
