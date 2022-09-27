<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

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
 *
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
class Membership extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id', 'member_id', 'role_id', 'function', 'email', 'membership_id', 'membership_type',
    ];

    protected $appends = ['role_email'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function scopeIsRole(Builder $query, Role $role_id): Builder
    {
        return $query->where('role_id', $role_id);
    }

    public function scopeIsNotRole(Builder $query, Role $role_id): Builder
    {
        return $query->where('role_id', '!=', $role_id);
    }

    public function getMasterEmailAttribute(): string
    {
        if ($this->email != '') {
            return $this->email;
        } elseif ($this->load('member')->member->email1 != '') {
            return $this->member->email1;
        } elseif ($this->member->email2 != '') {
            return $this->member->email2;
        } else {
            return 'fehlt';
        }
    }

    public function getRoleEmailAttribute(): string
    {
        if ($this->email != '') {
            return '('.Role::coerce($this->role_id)->description.': '.$this->email.')';
        } else {
            return '';
        }
    }

    public function getDescriptionAttribute(): string
    {
        $desc = Role::coerce($this->role_id)->description.' '.__('von').' ';
        if ($this->membership_type == Club::class) {
            $desc .= Club::find($this->membership_id)->shortname;
        } elseif ($this->membership_type == League::class) {
            $desc .= League::find($this->membership_id)->shortname;
        } elseif ($this->membership_type == Region::class) {
            $desc .= Region::find($this->membership_id)->code;
        } elseif ($this->membership_type == Team::class) {
            $desc .= Team::find($this->membership_id)->name_desc;
        }

        return $desc;
    }
}
