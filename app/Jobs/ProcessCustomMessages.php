<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Message;
use App\Models\User;
use App\Models\Club;
use App\Models\League;
use App\Models\Member;
use App\Notifications\CustomDbMessage;
//use App\Notifications\CustomMailMessage;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomMailMessage;
use App\Enums\MessageScopeType;
use App\Enums\MessageType;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProcessCustomMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
        Log::info('construct: '.print_r($this->message,true));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      app('view')->addNamespace('mail', resource_path('views/mail/html'));
      //Log::debug('handle 1:'.print_r($this->message,true));
      $mdest = Message::where('id', $this->message->id)->first()->destinations()->get();
      //Log::info('handle 2:'.print_r($mdest,true));

      $to_list = array();
      $cc_list = array();
      $drop_mail = false;

      foreach ($mdest as $d){
        // get scope
        if ($d->scope == MessageScopeType::Club ){
          // get in scope clubs
          $clubs = Club::clubRegion($d->region)->pluck('id');
          // get all club admim members
          $clubadmins = Member::whereHas('member_roles', function ($query) use($clubs){ $query->clubAdmin()->whereIn('unit_id',$clubs); })->get(['email1','firstname','lastname']);
          $adrlist = array();
          foreach ($clubadmins as $ca){
            $a = array();
            $a['email'] = $ca->email1;
            $a['name'] = $ca->firstname.' '.$ca->lastname;
            $adrlist[] = $a;
          }
          $drop_mail = true;
        } else if ($d->scope == MessageScopeType::League ){
          // get in scope leagues
          $leagues = League::leagueRegion($d->region)->pluck('id');
          // get all club admim members
          $leagueadmins = Member::whereHas('member_roles', function ($query) use($leagues) { $query->leagueAdmin()->whereIn('unit_id',$leagues); })->get(['email1','firstname','lastname']);
          $adrlist = array();
          foreach ($leagueadmins as $la){
            $a = array();
            $a['email'] = $la->email1;
            $a['name'] = $la->firstname.' '.$la->lastname;
            $adrlist[] = $a;
          }
          $drop_mail = true;
        } else if ($mdest->scope == MessageScopeType::Admin ){

          $drop_mail = false;
        } else if ($mdest->scope == MessageScopeType::User ){
          Log::info('will create user notifications in DB');
          $users = User::where('region', $d->region)->get();
          foreach ($users as $u){
            $u->notify(new CustomDbMessage($this->message));
          }
        }

        if ($drop_mail) {
          if ( $d->type == MessageType::fromValue(MessageType::to)->value ) {
            $to_list = array_merge($to_list, $adrlist);
          } else {
            $cc_list = array_merge($cc_list, $adrlist);
          }
        }
      }


      if ($drop_mail){
        Mail::to($to_list)
              ->cc($cc_list)
              ->send(new CustomMailMessage($this->message));
      }

      $this->message->update(['sent_at'=>Carbon::today()]);

    }
}
