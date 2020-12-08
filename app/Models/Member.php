<?php

namespace App\Models;

use App\Models\User;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;
use App\Enums\Role;
use App\Models\Membership;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{

  use Notifiable, HasFactory;

  protected $fillable = [
        'id','club_id','firstname','lastname','city','street', 'zipcode', 'phone1', 'phone2',
        'fax1', 'fax2', 'mobile', 'email1', 'email2'
    ];


  /**
   * Get tcompletion status
   *
   * @return string
   */
  public function getIsCompleteAttribute()
  {
    if ( (($this->firstname == '') and ($this->lastname == '') )
          or ($this->email1 == '')
          or (($this->mobile == '') and ($this->phone1 == ''))
          or (($this->zipcode == '') and ($this->city == '') and ($this->street==''))
       ){
      return false;
    } else {
      return true;
    }
  }

  public function getNameAttribute()
  {
    return $this->firstname.' '.$this->lastname;
  }

  public function memberships()
  {
      return $this->hasMany(Membership::class);
  }

  /**
   * Get the related user
   */
  public function user()
  {
      return $this->hasOne(User::class);
  }
  public function clubs()
  {
      return $this->morphedByMany(Club::class, 'membership' )->withPivot('role_id','function');
      // test: Member::find(261)->clubs()->get();
  }

  public function leagues()
  {
      return $this->morphedByMany(League::class, 'membership' )->withPivot('role_id','function');

  }

  // public function memberships()
  // {
  //   return $this->hasMany(Membership::class);
  // }

  public function region()
  {
      return $this->morphedByMany(Region::class, 'membership' )->withPivot('role_id','function');

  }
  public function getIsRegionAdminAttribute()
  {
    return $this->region()->wherePivot('role_id', Role::RegionLead)->exists();
  }

  /**
   * Route notifications for the mail channel.
   *
   * @param  \Illuminate\Notifications\Notification  $notification
   * @return array|string
   */
  public function routeNotificationForMail($notification)
  {
      // Return name and email address...
      return [$this->email1 => $this->firstname.' '.$this->lastname];
  }


}
