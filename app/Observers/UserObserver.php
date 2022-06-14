<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Club;
use App\Models\League;
use App\Enums\Role;
use App\Models\Member;
use App\Traits\UserAccessManager;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    use UserAccessManager;

    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        Log::info('[OBSERVER] user created - check for members', ['user-id' => $user->id]);

        $member = Member::with('memberships')->where('email1', $user->email)->orWhere('email2', $user->email)->orWhereHas('memberships', function ($q) use($user) { $q->where('email',$user->email);} )->first();
        if (isset($member)) {
            // link user and member
            $member->user()->save($user);
            $this->cloneMemberAccessRights($user);
            Log::info('[OBSERVER] user created - member found and linked', ['user-id' => $user->id, 'member-id' => $member->id]);
        }

    }



    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {

        if (($user->isDirty('approved_at')) and ($user->approved_at != null)) {
            Log::info('[OBSERVER] user updated - approved', ['user-id' => $user->id]);
            if (!$user->member()->exists()) {
                // else create the member witha  role = user
                $member = new Member(['lastname' => $user->name, 'email1' => $user->email]);
                $member->save();
                $member->user()->save($user);
                Log::notice('[OBSERVER] user updated - member with role user created', ['user-id' => $user->id, 'member-id' => $member->id]);
            }
        }
    }

    /**
     * Handle the User "deleting event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleting(User $user)
    {
        if ($user->member()->exists()) {
            $user->member()->dissociate();
            $user->save();
            // Log::notice('[OBSERVER] user deleted - delete associated member', ['user-id' => $user->id, 'member-id' => $user->member->id]);
            Log::notice('[OBSERVER] user deleted - ssociated member kept', ['user-id' => $user->id, 'member-id' => $user->member->id]);
        }
    }
}
