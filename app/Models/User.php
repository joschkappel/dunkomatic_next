<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Member;
use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable implements  MustVerifyEmail, CanResetPassword
{
    use Notifiable, HasFactory;

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
        'id','name', 'email', 'password','region','admin','regionadmin','approved_at','rejected_at','reason_join','reason_reject',
        'club_ids','league_ids','email_verified_at'
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
        return $this->hasOne('App\Models\Member');
    }

    public function user_region()
    {
        return $this->belongsTo('App\Models\Region','region','code');
    }
    public function messages()
    {
        return $this->hasMany('App\Models\Message','author');
    }

    public function scopeRegionadmin($query, $region)
    {
        return $query->where('region',$region)->where('regionadmin', true);
    }
    public function scopeRegion($query, $region)
    {
        return $query->where('region',$region);
    }
    public function scopeIsRole($query, $role)
    {
        return $query->first()->member->memberships()->isRole($role)->exists();
    }
    public function getLeagueFilecountAttribute()
    {
      $directory = $this->user_region->league_folder;
      $llist = $this->member->leagues()->pluck('shortname')->implode('|');
      $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
        return (preg_match('('.$llist.')', $value) === 1);
        //return (strpos($value,$llist[0]) !== false);
      });
      return count($reports);
    }
    public function getLeagueFilenamesAttribute()
    {
      $directory = $this->user_region->league_folder;
      $llist = $this->member->leagues()->pluck('shortname')->implode('|');
      $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
        return (preg_match('('.$llist.')', $value) === 1);
        //return (strpos($value,$llist[0]) !== false);
      });
      return $reports;
    }
    public function getClubFilecountAttribute()
    {
      $directory = $this->user_region->club_folder;
      $llist = $this->member->clubs()->pluck('shortname')->implode('|');
      $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
        return (preg_match('('.$llist.')', $value) === 1);
        //return (strpos($value,$llist[0]) !== false);
      });
      return count($reports);
    }
    public function getClubFilenamesAttribute()
    {
      $directory = $this->user_region->club_folder;
      $llist = $this->member->clubs()->pluck('shortname')->implode('|');
      $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($llist) {
        return (preg_match('('.$llist.')', $value) === 1);
        //return (strpos($value,$llist[0]) !== false);
      });
      return $reports;
    }
}
