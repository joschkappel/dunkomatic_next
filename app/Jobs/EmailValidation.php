<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Region;

use App\Notifications\InvalidEmail;
use App\Enums\Role;
use App\Models\Member;

use Validator;
use Illuminate\Support\Facades\Log;

class EmailValidation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Region $region;
    protected ?Member $region_admin;

    /**
     * Create a new job instance.
     *
     * @param Region $region
     * @return void
     *
     */
    public function __construct(Region $region)
    {
        $this->region = $region->load('regionadmins');
        //        $region_user = User::regionAdmin($this->region->code)->with('member')->first();
        $this->region_admins = $region->regionadmins;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rules = [
            'email1' => 'required|max:60|email:rfc,dns',
            'email2' => 'nullable|max:60|email:rfc,dns'
        ];

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


        Log::info('[JOB][EMAIL VALIDATION] started.', ['region-id' => $this->region->id]);
        $clubs = $this->region->clubs()->active()->get();

        foreach ($clubs as $c) {
            $members = $c->members()->get();
            $msgs = array();
            $invalid_emails = array();
            $fails = false;
            $club_lead = $c->members()->wherePivot('role_id', Role::ClubLead)->first();
            if (isset($club_lead)) {
                $lead_member = $club_lead;
            } else {
                $lead_member = null;
            };

            foreach ($members as $m) {
                $data = [
                    'email1' => $m->email1,
                    'email2' => $m->email2
                ];
                $validator = Validator::make($data,  $rules);

                if ($validator->fails()) {
                    if ($validator->messages()->has('email1')) {
                        $msgs[] = $m->name . ' mit ungültiger Haupt-eMail:  ' . $m->email1;
                        $invalid_emails[] = $m->email1;
                    } else {
                        $msgs[] = $m->name . ' mit ungültiger 2. eMail:  ' . $m->email2;
                        $invalid_emails[] = $m->email2;
                    }
                    $fails = true;
                }
            }

            if ($fails) {
                Log::warning('[JOB][EMAIL VALIDATION] found club members with an invalid email.',[ 'club-id'=>$c->id, 'count'=>count($msgs) ]);
                if ($lead_member == null) {
                    foreach ($this->region_admins as $ra){
                        $ra->notify(new InvalidEmail($c, null, $msgs));
                        Log::info('[NOTIFICATION][MEMBER] invalid email.', ['member-id' => $ra->id]);
                    }
                } elseif (in_array($lead_member->email1, $invalid_emails)) {
                    foreach ($this->region_admins as $ra){
                        $ra->notify(new InvalidEmail($c, null, $msgs));
                        Log::info('[NOTIFICATION][MEMBER] invalid email.', ['member-id' => $ra->id]);
                    }
                } else {
                    $lead_member->notify(new InvalidEmail($c, $this->region_admins->first(), $msgs));
                    Log::info('[NOTIFICATION][MEMBER] invalid email.', ['member-id' => $lead_member->id]);
                }
            }
        }
    }
}
