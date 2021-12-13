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

use App\Models\Member;
use App\Models\Region;
use App\Models\Message;
use App\Models\Club;
use App\Models\League;
use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Prunable;


class User extends Authenticatable implements MustVerifyEmail, CanResetPassword, HasLocalePreference
{
    use Notifiable, HasFactory, HasRolesAndAbilities, AuthenticationLoggable, Prunable;

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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'password', 'approved_at', 'rejected_at', 'reason_join', 'reason_reject',
        'email_verified_at', 'locale'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];
    // this is a recommended way to declare event handlers
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) { // before delete() method call this
            $user->messages()->delete();
        });
    }

    /**
     * Get the related member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function regions()
    {
        $user = $this;
        return Region::all()->filter(function ($region) use ($user) {
            return $user->can('access', $region);
        });
    }
    public function clubs()
    {
        $user = $this;
        return Club::all()->filter(function ($club) use ($user) {
            return $user->can('access', $club);
        });
    }
    public function leagues()
    {
        $user = $this;
        return League::all()->filter(function ($league) use ($user) {
            return $user->can('access', $league);
        });
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function region_messages($region_id)
    {
        return $this->messages()->where('region_id', $region_id);
    }


    public function scopeIsRole($query, $role)
    {
        return $query->first()->member->memberships()->where('role_id', $role)->exists();
    }

    /*
    * is this user admin of a region ( has regionlead membership ) ?
    */
    public function getIsRegionadminAttribute()
    {
        return $this->member->isRegionAdmin;
    }

    public function LeagueFilecount(Region $region)
    {
        $directory = $region->league_folder;
        if ($this->can('access', $region)) {
            $llist = $this->leagues()->pluck('shortname')->implode('|');
        }

        if ($llist !=  "") {
            $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
                return (preg_match('(' . $llist . ')', $value) === 1);
                //return (strpos($value,$llist[0]) !== false);
            });
            return count($reports);
        } else {
            return 0;
        }
    }
    public function LeagueFilenames(Region $region)
    {
        $directory = $region->league_folder;
        if ($this->can('access', $region)) {
            $llist = $this->leagues()->pluck('shortname')->implode('|');
        }
        if ($llist != "") {
            $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
                return (preg_match('(' . $llist . ')', $value) === 1);
                //return (strpos($value,$llist[0]) !== false);
            });
            return $reports;
        } else {
            return collect();
        }
    }
    public function TeamwareFilecount(Region $region)
    {
        $directory = $region->teamware_folder;
        if ($this->can('access', $region)) {
            $llist = $this->leagues()->pluck('shortname')->implode('|');
        }

        if ($llist !=  "") {
            $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
                return (preg_match('(' . $llist . ')', $value) === 1);
                //return (strpos($value,$llist[0]) !== false);
            });
            return count($reports);
        } else {
            return 0;
        }
    }
    public function TeamwareFilenames(Region $region)
    {
        $directory = $region->teamware_folder;
        if ($this->can('access', $region)) {
            $llist = $this->leagues()->pluck('shortname')->implode('|');
        }

        if ($llist != "") {
            $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
                return (preg_match('(' . $llist . ')', $value) === 1);
                //return (strpos($value,$llist[0]) !== false);
            });
            return $reports;
        } else {
            return collect();
        }
    }
    public function ClubFilecount(Region $region)
    {
        $directory = $region->club_folder;
        if ($this->can('access', $region)) {
            $llist = $this->clubs()->pluck('shortname')->implode('|');
        }

        if ($llist != "") {
            $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
                return (preg_match('(' . $llist . ')', $value) === 1);
                //return (strpos($value,$llist[0]) !== false);
            });
            return count($reports);
        } else {
            return 0;
        }
    }
    public function ClubFilenames(Region $region)
    {
        $directory = $region->club_folder;
        if ($this->can('access', $region)) {
            $llist = $this->clubs()->pluck('shortname')->implode('|');
        }

        if ($llist != "") {
            $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
                return (preg_match('(' . $llist . ')', $value) === 1);
                //return (strpos($value,$llist[0]) !== false);
            });
            return $reports;
        } else {
            return collect();
        }
    }
    public function notifyAuthenticationLogVia()
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
