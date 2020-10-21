<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Role;

class Membership extends Model
{
  protected $fillable = [
        'id','member_id','role_id','function','membershipable_id','membershipable_type'
    ];

  public function membershipable()
  {
      return $this->morphTo();
  }
  public function member()
  {
      return $this->belongsTo('App\Models\Member');
  }

  public function scopeIsRole($query, $role_id)
  {
    $query->where('role_id', $role_id);
  }
  public function scopeIsNotRole($query, $role_id)
  {
    $query->where('role_id', '!=', $role_id);
  }

}
