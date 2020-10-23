<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\Club;
use App\Models\Member;
use App\Models\Region;

use App\Notifications\InvalidEmail;
use App\Enums\Role;

use Validator;
use Illuminate\Support\Facades\Log;

class EmailValidation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $region;
    protected $region_admin;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region)
    {
        $this->region = $region;
        $region_user = User::regionAdmin($this->region->code)->with('member')->first();
        $this->region_admin = Member::find($region_user['member']->id)->first();

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $rules = [ 'email1' => 'required|max:60|email:rfc,dns',
                 'email2' => 'nullable|max:60|email:rfc,dns'];

      // SWITCHED OFF: USER EMAILS are validated at entry
      // $users = User::region($this->region->code)->get();
      // $rules = [ 'email1' => 'required|max:40|email:rfc,dns',
      //            'email2' => 'nullable|max:40|email:rfc,dns'];
      //
      // $invalid_emails = array();
      // foreach ($users as $u){
      //   $data = ['email1' => $u->email];
      //   $validator = Validator::make( $data,  $rules);
      //
      //   if ($validator->fails())
      //   {
      //       $invalid_emails[] = 'user '.$u->name.' with invalid email '.$u->email;
      //   }
      // }
      // Log::debug(print_r($invalid_emails,true));


      Log::info('job running: email validation for region '.$this->region->code);
      $clubs = Club::clubRegion($this->region->code)->get();

      foreach ($clubs as $c){
        $members = $c->members()->get();
        $msgs = array();
        $invalid_emails = array();
        $fails = false;
        $club_lead = $c->memberships()->isRole(Role::ClubLead)->with('member')->first();
        if (isset($club_lead['member'])){
          $lead_member = Member::find($club_lead['member']->id);
        } else {
          $lead_member = null;
        };

        foreach ($members as $m){
          $data = ['email1' => $m->email1,
                   'email2' => $m->email2];
          $validator = Validator::make( $data,  $rules);

          if ($validator->fails())
          {
              if ( $validator->messages()->has('email1') ){
                $msgs[] = $m->name.' with invalid main email '.$m->email1;
                $invalid_emails[] = $m->email1;
              } else {
                $msgs[] = $m->name.' with invalid alternative email '.$m->email2;
                $invalid_emails[] = $m->email2;
              }
              $fails = true;
          }
        }

        if ($fails){
          if ($lead_member == null){
            $this->region_admin->notify(new InvalidEmail($c, null, $msgs));
          } elseif (in_array($lead_member->email1, $invalid_emails)) {
            $this->region_admin->notify(new InvalidEmail($c, null, $msgs));
          } else {
            $lead_member->notify(new InvalidEmail($c, $this->region_admin, $msgs));
          }
        }
      }
    }
}
