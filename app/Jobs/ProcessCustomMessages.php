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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('[JOB][CUSTOM MESSAGE] started.', ['message' => $this->message]);
        $mdest = $this->message->message_destinations()->get();

        $to_list = array();
        $cc_list = array();
        $to_roles = array();
        $cc_roles = array();
        $drop_mail = false;

        foreach ($mdest as $d) {
            if ($d->role_id->in([Role::ClubLead, Role::RefereeLead, Role::JuniorsLead, Role::GirlsLead, Role::RegionLead, Role::RegionTeam])) {
                // get in scope clubs
                $clubs = $this->message->region->clubs;
                $members = collect();

                foreach ($clubs as $c){
                    // get all club admin members
                    $club_members = $c->members()->wherePivot('role_id', $d->role_id)->get(['email1', 'firstname', 'lastname']);
                    $members = $members->concat($club_members);
                }

                $drop_mail = true;
                Log::debug('[JOB][CUSTOM MESSAGE] club members to notify.', ['members' => $members->pluck('email1')]);
            } else if ($d->role_id->is(Role::LeagueLead)) {
                // get in scope leagues
                $leagues = $this->message->region->leagues;
                $members = collect();

                foreach ($leagues as $l){
                    // get all league admim members
                    $league_members = $l->members()->wherePivot('role_id', Role::LeagueLead)->get(['email1', 'firstname', 'lastname']);
                    $members = $members->concat($league_members);
                }

                $drop_mail = true;
                Log::debug('[JOB][CUSTOM MESSAGE] league members to notify.', ['members' => $members]);
            } else if ($d->role_id->is(Role::Admin)) {
                $members = $this->message->region->regionadmin;
                $drop_mail = true;
                Log::debug('[JOB][CUSTOM MESSAGE] region members to notify.', ['members' => $members]);
            } else if ($d->role_id->is(Role::User)) {
                $users = $this->message->region->users();
                foreach ($users as $u) {
                    $u->notify(new CustomDbMessage($this->message));
                }
                $to_roles[] = Role::getDescription($d->role_id) . ' ' . $this->message->region->code;
                Log::info('[JOB][CUSTOM MESSAGE] create user notifications in DB.',['users'=>$users]);
            }

            if ($drop_mail) {
                $adrlist = array();
                foreach ($members as $adr) {
                    if ($adr != null){
                        $a = array();
                        $a['email'] = $adr->email1;
                        $a['name'] = $adr->firstname . ' ' . $adr->lastname;
                        $adrlist[] = $a;
                    }
                }
                if ($d->type == MessageType::to()) {
                    $to_list = array_merge($to_list, $adrlist);
                    $to_roles[] = Role::getDescription($d->role_id) . ' ' . $this->message->region->code;
                } else {
                    $cc_list = array_merge($cc_list, $adrlist);
                    $cc_roles[] = Role::getDescription($d->role_id) . ' ' . $this->message->region->code;
                }
            }
        }


        if ($drop_mail) {
            Mail::to($to_list)
                ->cc($cc_list)
                ->send(new CustomMailMessage($this->message));
            Log::notice('[JOB][EMAIL] custom email sent.',['message-id'=>$this->message->id, 'to_count'=>count($to_list), 'cc_count'=>count($cc_list)]);
        }

        $this->message->update(['sent_at' => Carbon::today()]);
        $author = $this->message->user;

        $msg = __('To').' '. implode(', ', $to_roles);
        if (count($cc_roles) > 0) {
            $msg .= ', '.__('with copy to').' '. implode(', ', $cc_roles);
        }
        $author->notify(new AppActionMessage(__('Message').' "'. $this->message->title . '" '.__('sent'), $msg));
        Log::info('[JOB][NOTIFICATION][MEMBER] custom message sent.', ['member-id' => $author->id]);
    }
}
