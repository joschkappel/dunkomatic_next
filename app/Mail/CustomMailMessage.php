<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Message;

class CustomMailMessage extends Mailable
{
    use Queueable, SerializesModels;

    protected $message = array();
    protected $sender_name;
    protected $sender_email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
      $this->message = $message;

      $user = User::where('id', $message->author)->first();
      Log::info(print_r($user,true));
      $this->sender_name = $user['name'];
      $this->sender_email = $user['email'];

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
                  ->from([['email'=>$this->sender_email, 'name'=>$this->sender_name]]);

    }
}
