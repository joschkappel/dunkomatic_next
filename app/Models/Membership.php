<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Role;
use App\Models\Region;

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
  public function scopeIsRegionAdmin($query, $region_id)
  {
    $query->where('role_id', Role::RegionLead)->where('membershipable_id',$region_id)->where('membershipable_type', Region::class);
  }
  public function scopeIsNotRole($query, $role_id)
  {
    $query->where('role_id', '!=', $role_id);
  }

}
