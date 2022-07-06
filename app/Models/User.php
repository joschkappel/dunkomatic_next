<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Support\Facades\DB;

use App\Models\Member;
use App\Models\Region;
use App\Models\Message;
use App\Models\Club;
use App\Models\League;
use App\Notifications\VerifyEmail;
use App\Models\Invitation;

use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $provider
 * @property string|null $avatar
 * @property int|null $provider_id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $rejected_at
 * @property string|null $reason_join
 * @property string|null $reason_reject
 * @property string $locale
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $member_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Silber\Bouncer\Database\Ability[] $abilities
 * @property-read int|null $abilities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog[] $authentications
 * @property-read int|null $authentications_count
 * @property-read \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog|null $latestAuthentication
 * @property-read Member|null $member
 * @property-read \Illuminate\Database\Eloquent\Collection|Message[] $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Silber\Bouncer\Database\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static bool isRole($role)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIs($role)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAll($role)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsNot($role)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReasonJoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReasonReject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRejectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserOld($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail, CanResetPassword, HasLocalePreference
{
    use Notifiable, HasFactory, HasRolesAndAbilities, AuthenticationLoggable, Prunable;

    protected $with = ['member'];

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->locale;
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    protected $fillable = [
        'id', 'name', 'email', 'password', 'approved_at', 'rejected_at', 'reason_join', 'reason_reject',
        'email_verified_at', 'locale', 'provider', 'provider_id', 'avatar'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // this is a recommended way to declare event handlers
    public static function boot(): void
    {
        parent::boot();

        static::deleting(function ($user) { // before delete() method call this
            $user->messages()->delete();
        });
    }
    public function getInitialsAttribute(): string
    {
        $nameParts = explode(' ', trim($this->name));
        $firstName = array_shift($nameParts);
        $lastName = array_pop($nameParts);
        $initials = (
            mb_substr($firstName,0,1) .
            mb_substr($lastName,0,1)
        );
      return strtoupper($initials);
    }
    /**
     * Get the related member
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function regions(): Collection
    {
        // get all regions this user can access
        if ($this->isAn('superadmin')){
            return Region::all();
        } else {
            $regions = DB::table('abilities')
            ->join('permissions', 'abilities.id', '=', 'permissions.ability_id')
            ->where(function($query) {
                $query->where('abilities.name','access')
                      ->orWhere('abilities.name','coaccess');
            })
            ->where('abilities.entity_type', Region::class)
            ->where('permissions.entity_id', $this->id)
            ->where('permissions.entity_type', User::class)
            ->select('abilities.entity_id')
            ->get();
            return Region::whereIn('id', $regions->pluck('entity_id'))->get();
        }
    }
    public function clubs(): Collection
    {
        if ($this->isAn('superadmin')){
            return Club::all();
        } else {
            // get all clubs this user can access
            $clubs = DB::table('abilities')
            ->join('permissions', 'abilities.id', '=', 'permissions.ability_id')
            ->where('abilities.name','access')
            ->where('abilities.entity_type', Club::class)
            ->where('permissions.entity_id', $this->id)
            ->where('permissions.entity_type', User::class)
            ->select('abilities.entity_id')
            ->get();
            return Club::whereIn('id', $clubs->pluck('entity_id'))->get();
        }
    }
    public function leagues(): Collection
    {
        if ($this->isAn('superadmin')){
            return League::all();
        } else {
            // get all leagues this user can access
            $leagues = DB::table('abilities')
            ->join('permissions', 'abilities.id', '=', 'permissions.ability_id')
            ->where('abilities.name','access')
            ->where('abilities.entity_type', League::class)
            ->where('permissions.entity_id', $this->id)
            ->where('permissions.entity_type', User::class)
            ->select('abilities.entity_id')
            ->get();
            return League::whereIn('id', $leagues->pluck('entity_id'))->get();
        }
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }


    public function region_messages(int $region_id): HasMany
    {
        return $this->messages()->where('region_id', $region_id);
    }

    public function LeagueFilecount(Region $region): int
    {
        $directory = $region->league_folder;
        $llist = "";

        if ($this->can('access', $region)) {
            $llist = $this->leagues()->pluck('shortname')->implode('|');
        }

        if ($llist !=  "") {
            $reports = collect(Storage::files($directory))->filter(function ($value, $key) use ($llist) {
                return (preg_match('(' . $llist . ')', $value) === 1);
                // return Str::contains($llist, $value);
            });
            return count($reports);
        } else {
            return 0;
        }
    }
    public function LeagueFilenames(Region $region): Collection
    {
        $directory = $region->league_folder;
        $llist = "";

        if ($this->can('access', $region)) {
            $llist = $this->leagues()->pluck('shortname')->implode('|');
        }
        if ($llist != "") {
            $reports = collect(Storage::files($directory))->filter(function ($value, $key) use ($llist) {
                return (preg_match('(' . $llist . ')', $value) === 1);
                // return Str::contains($llist, $value);
            });
            return $reports;
        } else {
            return collect();
        }
    }

    public function TeamwareFilecount(Region $region): int
    {
        $directory = $region->teamware_folder;
        $llist= "";

        if ($this->can('access', $region)) {
            $llist = $this->leagues()->pluck('shortname')->implode('|');
        }

        if ($llist !=  "") {
            $reports = collect(Storage::files($directory))->filter(function ($value, $key) use ($llist) {
                return (preg_match('(' . $llist . ')', $value) === 1);
                // return Str::contains($llist, $value);
            });
            return count($reports);
        } else {
            return 0;
        }
    }

    public function TeamwareFilenames(Region $region): Collection
    {
        $directory = $region->teamware_folder;
        $llist = "";

        if ($this->can('access', $region)) {
            $llist = $this->leagues()->pluck('shortname')->implode('|');
        }

        if ($llist != "") {
            $reports = collect(Storage::files($directory))->filter(function ($value, $key) use ($llist) {
                return (preg_match('(' . $llist . ')', $value) === 1);
                // return Str::contains($llist, $value);
            });
            return $reports;
        } else {
            return collect();
        }
    }

    public function ClubFilecount(Region $region): int
    {
        $directory = $region->club_folder;
        $llist = "";

        if ($this->can('access', $region)) {
            $llist = $this->clubs()->pluck('shortname')->implode('|');
        }

        if ($llist != "") {
            $reports = collect(Storage::files($directory))->filter(function ($value, $key) use ($llist) {
                return (preg_match('(' . $llist . ')', $value) === 1);
                // return Str::contains($llist, $value);
            });
            return count($reports);
        } else {
            return 0;
        }
    }

    public function ClubFilenames(Region $region): Collection
    {
        $directory = $region->club_folder;
        $llist = "";

        if ($this->can('access', $region)) {
            $llist = $this->clubs()->pluck('shortname')->implode('|');
        }

        if ($llist != "") {
            $reports = collect(Storage::files($directory))->filter(function ($value, $key) use ($llist) {
                return (preg_match('(' . $llist . ')', $value) === 1);
                // return Str::contains($llist, $value);
            });
            return $reports;
        } else {
            return collect();
        }
    }

    public function notifyAuthenticationLogVia(): array
    {
        return ['mail'];
    }

    /**
     * Get the prunable model query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function prunable()
    {
        Log::notice('[JOB][DB CLEANUP] pruning users.');
        return static::where('rejected_at', '<', now()->subWeek())
            ->orWhere(function ($query) {
                $query->whereNull('email_verified_at')
                    ->where('created_at', '<', now()->subMonth());
            });
    }
}
