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

  public static $createRules = [
      'firstname' => 'required|max:20',
      'lastname' => 'required|max:60',
      'zipcode' => 'required|max:10',
      'city' => 'required|max:40',
      'street' => 'required|max:40',
      'mobile' => 'required_without:phone1|max:40',
      'phone1' => 'required_without:mobile|max:40',
      'phone2' => 'max:40',
      'fax1' => 'max:40',
      'fax2' => 'max:40',
      'email1' => 'required|max:60|email:rfc,dns',
      'email2' => 'nullable|max:60|email:rfc,dns',
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
  public function getMemberOfClubsAttribute()
  {
    return $this->clubs()->wherePivotIn('role_id', [Role::ClubLead, Role::RefereeLead, Role::GirlsLead, Role::JuniorsLead, Role::User])->pluck('shortname')->unique()->implode(', ');
  }
  public function getMemberOfLeaguesAttribute()
  {
    return $this->leagues()->wherePivotIn('role_id', [Role::LeagueLead, Role::User])->pluck('shortname')->unique()->implode(', ');
  }
  public function getMemberOfRegionAttribute()
  {
    return $this->region()->wherePivotIn('role_id', [Role::RegionLead, Role::RegionTeam, Role::User])->pluck('code')->unique()->implode('-');
  }
  public function getIsUserAttribute()
  {
    return $this->user()->exists();
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
