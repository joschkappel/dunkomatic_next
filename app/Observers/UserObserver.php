<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Club;
use App\Models\League;
use App\Enums\Role;
use App\Models\Member;

use Illuminate\Support\Facades\Log;
class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
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
            Log::info('observer: user updated - approved', ['user-id' => $user->id]);
            if (!$user->member()->exists()) {
                // else create the member witha  role = user
                $member = new Member(['lastname' => $user->name, 'email1' => $user->email]);
                $member->save();
                $member->user()->save($user);
                Log::notice('observer: user updated - member with role user created', ['user-id' => $user->id, 'member-id'=>$member->id]);
            }
        }

        if ($user->member()->exists()) {
            Log::info('observer: user updated - member exists', ['user-id' => $user->id]);
            $member = $user->member;
            $member->clubs()->wherePivot('role_id', Role::User)->detach();
            $member->leagues()->wherePivot('role_id', Role::User)->detach();

            $abilities = $user->getAbilities();

            foreach ($abilities as $a) {
                if ($a->entity_type == Club::class) {
                    $member->clubs()->attach($a->entity_id, array('role_id' => Role::User));
                } elseif ($a->entity_type == League::class) {
                    $member->leagues()->attach($a->entity_id, array('role_id' => Role::User));
                }
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        if ($user->member->memberships()->isNotRole(Role::User)->count() == 0) {
            // delete user, member and memberships - he is "Only" a user
            $member = Member::find($user->member->id);
            $member->memberships()->delete();
            $member->delete();
            Log::notice('observer: user deleted - delete associated member', ['user-id' => $user->id, 'member-id'=>$member->id]);
        } else {
            // delete only the user and detach from member
            $member = Member::find($user->member->id);
            $member->memberships()->where('role_id', Role::User)->delete();
            Log::notice('observer: user deleted - delete user membership', ['user-id' => $user->id, 'member-id'=>$member->id]);
        }
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
