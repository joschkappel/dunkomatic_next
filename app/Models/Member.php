<?php

namespace App\Models;

use App\Models\User;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;
use App\Enums\Role;
use App\Models\Membership;
use App\Models\Invitation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * App\Models\Member
 *
 * @property int $id
 * @property string|null $firstname
 * @property string $lastname
 * @property string|null $city
 * @property string|null $zipcode
 * @property string|null $street
 * @property string|null $phone
 * @property string|null $mobile
 * @property string $email1
 * @property string|null $email2
 * @property string|null $fax
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Club[] $clubs
 * @property-read int|null $clubs_count
 * @property-read bool $is_region_admin
 * @property-read mixed $is_user
 * @property-read mixed $member_of_clubs
 * @property-read mixed $member_of_leagues
 * @property-read mixed $member_of_region
 * @property-read string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|League[] $leagues
 * @property-read int|null $leagues_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Membership[] $memberships
 * @property-read int|null $memberships_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Region[] $region
 * @property-read int|null $region_count
 * @property-read User|null $user
 * @method static \Database\Factories\MemberFactory factory(...$parameters)
 * @method static Builder|Member newModelQuery()
 * @method static Builder|Member newQuery()
 * @method static Builder|Member query()
 * @method static Builder|Member whereCity($value)
 * @method static Builder|Member whereCreatedAt($value)
 * @method static Builder|Member whereEmail1($value)
 * @method static Builder|Member whereEmail2($value)
 * @method static Builder|Member whereFax($value)
 * @method static Builder|Member whereFirstname($value)
 * @method static Builder|Member whereId($value)
 * @method static Builder|Member whereLastname($value)
 * @method static Builder|Member whereMobile($value)
 * @method static Builder|Member wherePhone($value)
 * @method static Builder|Member whereStreet($value)
 * @method static Builder|Member whereUpdatedAt($value)
 * @method static Builder|Member whereZipcode($value)
 * @mixin \Eloquent
 */

class Member extends Model
{

  use Notifiable, HasFactory;

  protected $fillable = [
        'id','club_id','firstname','lastname','city','street', 'zipcode', 'phone',
        'fax', 'mobile', 'email1', 'email2'
    ];

  public function getNameAttribute(): string
  {
    return $this->firstname.' '.$this->lastname;
  }

  public function memberships(): HasMany
  {
      return $this->hasMany(Membership::class);
  }
  public function invitations(): HasMany
  {
      return $this->hasMany(Invitation::class);
  }

  /**
   * Get the related user
   */
  public function user(): HasOne
  {
      return $this->hasOne(User::class);
  }
  public function clubs(): MorphToMany
  {
      return $this->morphedByMany(Club::class, 'membership' )->withPivot('role_id','function');
      // test: Member::find(261)->clubs()->get();
  }

  public function leagues(): MorphToMany
  {
      return $this->morphedByMany(League::class, 'membership' )->withPivot('role_id','function');

  }

  public function region(): MorphToMany
  {
      return $this->morphedByMany(Region::class, 'membership' )->withPivot('role_id','function');

  }
  public function getIsRegionAdminAttribute(): bool
  {
    return $this->region()->wherePivot('role_id', Role::RegionLead)->exists();
  }
  public function getMemberOfClubsAttribute(): string
  {
    return $this->clubs()->wherePivotIn('role_id', [Role::ClubLead, Role::RefereeLead, Role::GirlsLead, Role::JuniorsLead])->pluck('shortname')->unique()->implode(', ');
  }
  public function getMemberOfLeaguesAttribute(): string
  {
    return $this->leagues()->wherePivotIn('role_id', [Role::LeagueLead])->pluck('shortname')->unique()->implode(', ');
  }
  public function getMemberOfRegionAttribute(): string
  {
    return $this->region()->wherePivotIn('role_id', [Role::RegionLead, Role::RegionTeam])->pluck('code')->unique()->implode('-');
  }
  public function getIsUserAttribute(): bool
  {
    return $this->user()->exists();
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
