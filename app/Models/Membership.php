<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use App\Enums\Role;
use App\Models\Region;
use App\Models\Member;

class Membership extends Model
{
  protected $fillable = [
        'id','member_id','role_id','function','membership_id','membership_type'
    ];

  public function member()
  {
      return $this->belongsTo(Member::class);
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
