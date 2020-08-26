<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable implements  MustVerifyEmail, CanResetPassword
{
    use Notifiable;

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

    public function adminlte_profile_url()
    {
        return 'admin.user.show';
    }
    /**
     * Get all of the clubs that are assigned this user.
     */
    public function clubs()
    {
        return $this->morphedByMany('App\Club', 'useable');
    }

    /**
     * Get all of the leagues that are assigned this user.
     */
    public function leagues()
    {
        return $this->morphedByMany('App\League', 'useable');
    }

    public function user_region()
    {
        return $this->belongsTo('App\Region','region','code');
    }

    public function scopeRegionadmin($query, $region)
    {
        return $query->where('region',$region)->where('regionadmin', true);
    }
    public function scopeRegion($query, $region)
    {
        return $query->where('region',$region);
    }
}
