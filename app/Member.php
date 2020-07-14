<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
  protected $fillable = [
        'id','club_id','firstname','lastname','city','street', 'zipcode', 'phone1', 'phone2',
        'fax1', 'fax2', 'mobile', 'email1', 'email2'
    ];

  public function member_roles()
  {
      return $this->hasMany('App\MemberRole');
  }

}
