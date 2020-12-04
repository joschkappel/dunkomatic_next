<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Silber\Bouncer\Database\HasRolesAndAbilities;

use App\Models\Member;
use App\Models\Region;
use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable implements  MustVerifyEmail, CanResetPassword
{
    use Notifiable, HasFactory, HasRolesAndAbilities;

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
        'id','name', 'email', 'password','region','approved_at','rejected_at','reason_join','reason_reject',
        'email_verified_at'
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

    /**
     * Get the related member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user_region()
    {
        return $this->belongsTo('App\Models\Region','region','code');
    }
    public function messages()
    {
        return $this->hasMany('App\Models\Message','author');
    }

    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting(function($user) { // before delete() method call this
             $user->messages()->delete();
        });
    }

    public function scopeRegion($query, $region)
    {
        return $query->where('region',$region);
    }
    public function scopeIsRole($query, $role)
    {
        return $query->first()->member->memberships()->isRole($role)->exists();
    }

    /*
    * is this user admin of a region ( has regionlead membership ) ?
    */
    public function getIsRegionadminAttribute()
    {
        return $this->member->memberships()->isRegionAdmin($this->user_region->id)->exists();
    }

    public function getLeagueFilecountAttribute()
    {
      $directory = $this->user_region->league_folder;
      $llist = $this->member->leagues()->pluck('shortname')->implode('|');
      if ($llist !=  ""){
        $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
          return (preg_match('('.$llist.')', $value) === 1);
          //return (strpos($value,$llist[0]) !== false);
        });
        return count($reports);
      } else {
        return 0;
      }
    }
    public function getLeagueFilenamesAttribute()
    {
      $directory = $this->user_region->league_folder;
      $llist = $this->member->leagues()->pluck('shortname')->implode('|');
      if ($llist != ""){
        $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
          return (preg_match('('.$llist.')', $value) === 1);
          //return (strpos($value,$llist[0]) !== false);
        });
        return $reports;
      } else {
        return collect();
      }
    }
    public function getClubFilecountAttribute()
    {
      $directory = $this->user_region->club_folder;
      $llist = $this->member->clubs()->pluck('shortname')->implode('|');
      if ($llist != "" ){
        $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
          return (preg_match('('.$llist.')', $value) === 1);
          //return (strpos($value,$llist[0]) !== false);
        });
        return count($reports);
      } else {
        return 0;
      }
    }
    public function getClubFilenamesAttribute()
    {
      $directory = $this->user_region->club_folder;
      $llist = $this->member->clubs()->pluck('shortname')->implode('|');
      if ($llist != ""){
        $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
          return (preg_match('('.$llist.')', $value) === 1);
          //return (strpos($value,$llist[0]) !== false);
        });
        return $reports;
      } else {
        return collect();
      }
    }
}
