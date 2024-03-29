<?php

namespace App\Jobs;

use App\Enums\Role as EnumRole;
use App\Mail\CustomMailMessage;
use App\Models\Club;
use App\Models\Message;
use App\Models\User;
use App\Notifications\AppActionMessage;
use App\Notifications\CustomDbMessage;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SendCustomMessage implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Message $message;

    /**
     * Create a new job instance.
     *
     * @param  Message  $message
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

        $to_members = collect();
        $to_member_roles = [];
        $cc_members = collect();
        $cc_members->push(['name' => $this->message->user->name, 'email' => $this->message->user->email]);
        $cc_member_roles = [];
        $to_users = collect();

        if ($this->message->region->is_top_level) {
            $clubs = $this->message->region->clubs()->active()->get();
            $clubs_base = Club::whereIn('region_id', $this->message->region->childRegions->pluck('id'))->active()->get();
            $clubs = $clubs->concat($clubs_base);
        } else {
            $clubs = $this->message->region->clubs()->active()->get();
        }

        $leagues = $this->message->region->leagues;
        $regions = $this->message->region->get();

        foreach ($this->message->to_members ?? [] as $tm) {
            // send eMails to members
            $tm = EnumRole::coerce(intval($tm));
            $to_member_roles[] = $tm->description.' '.$this->message->region->code;

            // collect all members based on role
            if ($tm->in([EnumRole::ClubLead(), EnumRole::RefereeLead(), EnumRole::JuniorsLead(), EnumRole::GirlsLead()])) {
                // get in scope clubs
                foreach ($clubs as $c) {
                    $to_members = $to_members->concat($c->members()->wherePivot('role_id', $tm)->get());
                }
            } elseif ($tm->is(EnumRole::LeagueLead())) {
                // get in scope leagues
                foreach ($leagues as $l) {
                    $to_members = $to_members->concat($l->members()->wherePivot('role_id', EnumRole::LeagueLead)->get());
                }
            } elseif ($tm->in([EnumRole::RegionLead(), EnumRole::RegionTeam()])) {
                // get in scope regions
                foreach ($regions as $r) {
                    $to_members = $to_members->concat($r->members()->wherePivot('role_id', $tm)->get());
                }
            }
            Log::info('[JOB][CUSTOM MESSAGE] members to email.', ['members' => $to_members->count()]);
        }
        foreach ($this->message->cc_members ?? [] as $cm) {
            // send eMails to members
            $cm = EnumRole::coerce(intval($cm));
            $cc_member_roles[] = $cm->description.' '.$this->message->region->code;

            // collect all members based on role
            if ($cm->in([EnumRole::ClubLead(), EnumRole::RefereeLead(), EnumRole::JuniorsLead(), EnumRole::GirlsLead()])) {
                // get in scope clubs
                foreach ($clubs as $c) {
                    $cc_members = $cc_members->concat($c->members()->wherePivot('role_id', $cm)->get());
                }
            } elseif ($cm->is(EnumRole::LeagueLead())) {
                // get in scope leagues
                foreach ($leagues as $l) {
                    $cc_members = $cc_members->concat($l->members()->wherePivot('role_id', EnumRole::LeagueLead)->get());
                }
            } elseif ($cm->in([EnumRole::RegionLead(), EnumRole::RegionTeam()])) {
                // get in scope regions
                foreach ($regions as $r) {
                    $cc_members = $cc_members->concat($r->members()->wherePivot('role_id', $cm)->get());
                }
            }
            Log::info('[JOB][CUSTOM MESSAGE] members cc email.', ['members' => $cc_members->count()]);
        }

        if ($to_members->count() >= $cc_members->count()) {
            $to_chunks = $to_members->chunk(60);
            $cc_chunks = $cc_members->split($to_chunks->count());
            foreach ($to_chunks as $i => $chunk) {
                Mail::to($chunk ?? [])
                    ->cc($cc_chunks[$i] ?? [])
                    ->send(new CustomMailMessage($this->message));
                Log::notice('[JOB][EMAIL] custom email sent.', ['message-id' => $this->message->id, 'to_count' => (($chunk ?? collect())->count()), 'cc_count' => (($cc_chunks[$i] ?? collect())->count())]);
            }
        } else {
            $cc_chunks = $cc_members->chunk(60);
            $to_chunks = $to_members->split($cc_chunks->count());
            foreach ($cc_chunks as $i => $chunk) {
                Mail::to($to_chunks[$i] ?? [])
                    ->cc($chunk ?? [])
                    ->send(new CustomMailMessage($this->message));
                Log::notice('[JOB][EMAIL] custom email sent.', ['message-id' => $this->message->id, 'cc_count' => ($chunk ?? collect())->count(), 'to_count' => ($to_chunks[$i] ?? collect())->count()]);
            }
        }

        //  get users
        if ($this->message->notify_users) {
            $to_users = $this->message->region->users();
            $to_users = $to_users->concat(User::whereIs('superadmin')->get());

            if ($this->message->region->is_top_level) {
                foreach ($this->message->region->childRegions() as $cr) {
                    $to_users = $to_users->concat($cr->users());
                }
            }
        }

        $this->message->update(['sent_at' => Carbon::today()]);
        $author = $this->message->user;
        $msg = '';

        if (count($to_users) > 0) {
            Notification::sendNow($to_users, new CustomDbMessage($this->message));
            Log::notice('[JOB][NOTIFICATION] custom notification sent.', ['message-id' => $this->message->id, 'to_count' => count($to_users)]);
        }

        if (count($to_member_roles) > 0) {
            $msg .= __('Mail to').' '.implode(', ', $to_member_roles);
            if (count($cc_member_roles) > 0) {
                $msg .= ', '.__('with copy to').' '.implode(', ', $cc_member_roles).' '.__('has been sent.');
            }
        }
        if ($this->message->notify_users) {
            $msg .= __('Notification to region users has been posted.');
        }
        $author->notify(new AppActionMessage(__('Message').' "'.$this->message->title.'" '.__('sent'), $msg));
        Log::info('[JOB][NOTIFICATION][MEMBER] custom message sent.', ['member-id' => $author->id]);
    }
}
