<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

/**
 * App\Models\Membership
 *
 * @property int $id
 * @property int $member_id
 * @property string $membership_type
 * @property int $membership_id
 * @property int $role_id
 * @property string|null $function
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|Membership isNotRole($role_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership isRole($role_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership query()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereFunction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereMembershipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereMembershipType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Membership extends Model
{
  protected $fillable = [
        'id','member_id','role_id','function','email', 'membership_id','membership_type'
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
