<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Member extends Model
{

  use Notifiable;

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

  public function memberships()
  {
      return $this->hasMany('App\Models\Membership');
  }

  /**
   * Get the related user
   */
  public function user()
  {
      return $this->belongsTo('App\Models\User','user_id','id');
  }
  public function clubs()
  {
      return $this->morphedByMany('App\Models\Club', 'membershipable', 'memberships',  'member_id', 'membershipable_id' )->withPivot('role_id','function','id');
      // test: Member::find(261)->clubs()->get();
  }
  public function leagues()
  {
      return $this->morphedByMany('App\Models\League', 'membershipable', 'memberships',  'member_id', 'membershipable_id' )->withPivot('role_id','function','id');

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
