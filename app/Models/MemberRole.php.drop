<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Role;

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
      return $this->belongsTo('App\Models\Member');
  }

  public function scopeIsRole($query, $role_id)
  {
    $query->where('role_id', $role_id);
  }

}
