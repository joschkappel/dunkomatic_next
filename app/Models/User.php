<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Member;

class User extends Authenticatable implements  MustVerifyEmail, CanResetPassword
{
    use Notifiable, HasFactory;

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
}
