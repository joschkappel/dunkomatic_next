<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Message;
use App\Models\User;
use App\Notifications\CustomDbMessage;
use App\Notifications\AppActionMessage;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomMailMessage;
use App\Enums\Role as EnumRole;
use Silber\Bouncer\Database\Role as UserRole;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class SendCustomMessage implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Message $message;

    /**
     * Create a new job instance.
     *
     * @param Message $message
     * @return void
     *
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

        $to_members = collect();
        $to_member_roles = array();
        $cc_members = collect();
        $cc_member_roles = array();
        $to_users = collect();

        $clubs = $this->message->region->clubs()->active()->get();
        $leagues = $this->message->region->leagues;
        $regions = $this->message->region->get();

        foreach ($this->message->to_members ?? [] as $tm) {
            // send eMails to members
            $tm = EnumRole::coerce(intval($tm));
            $to_member_roles[] = $tm->description . ' ' . $this->message->region->code;

            // collect all members based on role
            if ( $tm->in([EnumRole::ClubLead(), EnumRole::RefereeLead(), EnumRole::JuniorsLead(), EnumRole::GirlsLead()]) ) {
                // get in scope clubs
                foreach ($clubs as $c) {
                    $to_members = $to_members->concat( $c->members()->wherePivot('role_id', $tm)->get()->transform(function ($item, $key) { return ['name'=>$item->name,'email'=>$item->email1];}) );
                }
            } else if ( $tm->is(EnumRole::LeagueLead()) ) {
                // get in scope leagues
                foreach ($leagues as $l) {
                    $to_members = $to_members->concat( $l->members()->wherePivot('role_id', EnumRole::LeagueLead)->get()->transform(function ($item, $key) { return ['name'=>$item->name,'email'=>$item->email1];}) );
                }
            } else if ( $tm->in([ EnumRole::RegionLead(), EnumRole::RegionTeam() ]) ) {
                // get in scope regions
                foreach ($regions as $r) {
                    $to_members = $to_members->concat( $r->members()->wherePivot('role_id', $tm)->get()->transform(function ($item, $key) { return ['name'=>$item->name,'email'=>$item->email1];}) );
                }
            }
            Log::info('[JOB][CUSTOM MESSAGE] members to email.', ['members' => $to_members->count()]);
        }
        foreach ($this->message->cc_members ?? [] as $cm) {
            // send eMails to members
            $cm = EnumRole::coerce(intval($cm));
            $cc_member_roles[] = $cm->description . ' ' . $this->message->region->code;

            // collect all members based on role
            if ( $cm->in([EnumRole::ClubLead(), EnumRole::RefereeLead(), EnumRole::JuniorsLead(), EnumRole::GirlsLead()]) ) {
                // get in scope clubs
                foreach ($clubs as $c) {
                    $cc_members = $cc_members->concat( $c->members()->wherePivot('role_id', $cm)->get()->transform(function ($item, $key) { return ['name'=>$item->name,'email'=>$item->email1];}) );
                }
            } elseif ( $cm->is(EnumRole::LeagueLead()) ) {
                // get in scope leagues
                foreach ($leagues as $l) {
                    $cc_members = $cc_members->concat( $l->members()->wherePivot('role_id', EnumRole::LeagueLead)->get()->transform(function ($item, $key) { return ['name'=>$item->name,'email'=>$item->email1];}) );
                }
            } elseif ( $cm->in([ EnumRole::RegionLead(), EnumRole::RegionTeam() ]) ) {
                // get in scope regions
                foreach ($regions as $r) {
                    $cc_members = $cc_members->concat( $r->members()->wherePivot('role_id', $cm)->get()->transform(function ($item, $key) { return ['name'=>$item->name,'email'=>$item->email1];}) );
                }
            }
            Log::info('[JOB][CUSTOM MESSAGE] members cc email.', ['members' => $cc_members->count()]);
        }

        if (count($to_members) > 0) {
            Mail::to($to_members)
                ->cc($cc_members)
                ->send(new CustomMailMessage($this->message));
            Log::notice('[JOB][EMAIL] custom email sent.', ['message-id' => $this->message->id, 'to_count' => count($to_members), 'cc_count' => count($cc_members)]);
        }

        $user_region = $this->message->region;
        $user_roles = UserRole::whereIn('id', $this->message->to_users ?? [])->pluck('name');

        foreach ($user_roles as $ur){
            $to_users = $to_users->concat( User::whereIs($ur)->get()->filter(function ($value, $key) use ($user_region) { return $value->can('access',$user_region);}) );
        }
        if (count($to_users) > 0){
            Notification::sendNow($to_users, new CustomDbMessage($this->message));
            Log::notice('[JOB][NOTIFICATION] custom note sent.', ['message-id' => $this->message->id, 'to_count' => count($to_users)]);
        }

        $this->message->update(['sent_at' => Carbon::today()]);
        $author = $this->message->user;
        $msg = '';

        if (count($to_member_roles) > 0) {
            $msg .= __('Mail to') . ' ' . implode(', ', $to_member_roles);
            if (count($cc_member_roles) > 0) {
                $msg .= ', ' . __('with copy to') . ' ' . implode(', ', $cc_member_roles) . ' '.__('has been sent.');
            }
        }
        if (count($user_roles) > 0) {
            $msg .= __('Notification to') . ' ' . $user_roles->implode(', '). ' '.__('has been posted.');
        }
        $author->notify(new AppActionMessage(__('Message') . ' "' . $this->message->title . '" ' . __('sent'), $msg));
        Log::info('[JOB][NOTIFICATION][MEMBER] custom message sent.', ['member-id' => $author->id]);
    }
}
