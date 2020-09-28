<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberRole extends Model
{
  protected $fillable = [
        'id','member_id','role_id','function','unit_id','unit_type'
    ];

  public function unit()
  {
      return $this->morphTo();
  }

  public function member()
  {
      return $this->belongsTo('App\Member');
  }

  public function role()
  {
      return $this->belongsTo('App\Role');
  }
}
