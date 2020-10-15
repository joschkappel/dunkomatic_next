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

  public function member_roles()
  {
      return $this->hasMany('App\Models\MemberRole');
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
