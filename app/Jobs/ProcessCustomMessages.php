<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Message;
use App\Models\User;
use App\Models\Region;
use App\Models\Club;
use App\Models\League;
use App\Models\Member;
use App\Notifications\CustomDbMessage;
use App\Notifications\AppActionMessage;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomMailMessage;
use App\Enums\Role;
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
      //app('view')->addNamespace('mail', resource_path('views/mail/html'));
      //Log::debug('handle 1:'.print_r($this->message,true));
      $mdest = Message::where('id', $this->message->id)->first()->destinations()->get();
      //Log::info('handle 2:'.print_r($mdest,true));

      $to_list = array();
      $cc_list = array();
      $to_roles = array();
      $cc_roles = array();
      $drop_mail = false;

      foreach ($mdest as $d){
        // get scope
        $role = Role::fromValue($d->scope);

        if ($role->in([Role::ClubLead, Role::RefereeLead, Role::JuniorsLead, Role::GirlsLead, Role::RegionLead, Role::RegionTeam]) ){
          // get in scope clubs
          $clubs = $d->region->clubs()->pluck('id');
          // get all club admim members
          //$members = Member::whereHas('memberships', function ($query) use($clubs,$role){ $query->isRole($role)->whereIn('membershipable_id',$clubs); })->get(['email1','firstname','lastname']);
          $members = array();
          foreach ($clubs as $c){
            $members[] = Club::find($c)->members()->wherePivot('role_id', $role)->get(['email1','firstname','lastname']);
          }
          $drop_mail = true;
        } else if ($role->is(Role::LeagueLead) ){
          // get in scope leagues
          $leagues = $d->region->leagues()->pluck('id');
          // get all club admim members
          //$members = Member::whereHas('memberships', function ($query) use($leagues) { $query->isRole(Role::LeagueLead)->whereIn('membershipable_id',$leagues); })->get(['email1','firstname','lastname']);
          $members = array();
          foreach ($leagues as $l){
            $members[] = League::find($l)->members()->wherePivot('role_id', Role::LeagueLead)->get(['email1','firstname','lastname']);
          }
          $drop_mail = true;
        } else if ($role->is( Role::Admin )){
          $users = Region::where('code',$d->region)->first()->regionadmin->first()->user()->get();
          foreach ($users as $u){
            $u->notify(new CustomDbMessage($this->message));
          }
        } else if ($role->is(Role::User) ){
          Log::info('will create user notifications in DB');
          $users = User::region($d->region)->get();
          foreach ($users as $u){
            $u->notify(new CustomDbMessage($this->message));
          }
        }

        if ($drop_mail) {
          $adrlist = array();
          foreach ($members as $adr){
            $a = array();
            $a['email'] = $adr->email1;
            $a['name'] = $adr->firstname.' '.$adr->lastname;
            $adrlist[] = $a;
          }
          if ( $d->type == MessageType::to ) {
            $to_list = array_merge($to_list, $adrlist);
            $to_roles[] = Role::getDescription($role).' '.$d->region;
          } else {
            $cc_list = array_merge($cc_list, $adrlist);
            $cc_roles[] = Role::getDescription($role).' '.$d->region;
          }
        }
      }


      if ($drop_mail){
        Mail::to($to_list)
              ->cc($cc_list)
              ->send(new CustomMailMessage($this->message));
      }

      $this->message->update(['sent_at'=>Carbon::today()]);
      $author = User::where('id',$this->message->author)->first();

      $msg = 'Message send to '.implode(', ', $to_roles).'. With copy to '.implode(', ', $cc_roles);
      $author->notify(new AppActionMessage('Message '.$this->message->title.' sent', $msg));

    }
}
