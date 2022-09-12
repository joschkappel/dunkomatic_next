<?php

namespace App\Models;

use App\Models\User;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;
use App\Models\Team;
use App\Enums\Role;
use App\Models\Membership;
use App\Models\Invitation;

use OwenIt\Auditing\Contracts\Auditable;

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

class Member extends Model implements Auditable
{

  use \OwenIt\Auditing\Auditable, Notifiable, HasFactory;

  protected $fillable = [
        'id','club_id','firstname','lastname','city','street', 'zipcode', 'phone',
        'fax', 'mobile', 'email1', 'email2'
    ];
  protected $appends = [ 'name', 'email','address', 'member_of_clubs','member_of_leagues','club_memberships','league_memberships','is_user'];
  protected $with = ['memberships'];

  public function getNameAttribute(): string
  {
    return $this->firstname.' '.$this->lastname;
  }

  public function memberships(): HasMany
  {
      return $this->hasMany(Membership::class);
  }
  public function invitation(): HasOne
  {
      return $this->hasOne(Invitation::class);
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
      return $this->morphedByMany(Club::class, 'membership' )->withPivot('role_id','function')->without('region');
      // test: Member::find(261)->clubs()->get();
  }

  public function leagues(): MorphToMany
  {
      return $this->morphedByMany(League::class, 'membership' )->withPivot('role_id','function')->without('region');

  }

  public function region(): MorphToMany
  {
      return $this->morphedByMany(Region::class, 'membership' )->withPivot('role_id','function');

  }
  public function getEmailAttribute(): string
  {
    return (( $this->email1 == '' ? $this->email2 : $this->email1) ?? '');
  }
  public function getAddressAttribute(): string
  {
     return "{$this->street}, {$this->zipcode} {$this->city}";
  }
  public function getIsRegionAdminAttribute(): bool
  {
    return $this->region()->wherePivot('role_id', Role::RegionLead)->exists();
  }
  public function getMemberOfClubsAttribute(): string
  {
    return $this->clubs()->pluck('shortname')->unique()->implode(', ');
  }
  public function getClubMembershipsAttribute(): string
  {
    $club = $this->load('clubs')->clubs;
    $title = collect();
    foreach ($club as $c){
        $title->push(Role::coerce($c->pivot->role_id)->description.' '.$c->shortname);
        if ($c->pivot->role_id == Role::ClubLead){
            $leagues = $c->load('teams.league')->teams->whereNotNull('league_id')->pluck('league.shortname')->implode(', ');
            if ($leagues != ''){
                $title->push( '('.$leagues.')');
            }
        }
    }
    return $title->implode(', ');
  }
  public function getLeagueMembershipsAttribute(): string
  {
    $leagues = $this->load('leagues')->leagues;
    $title = collect();
    foreach ($leagues as $l){
        $title->push(Role::coerce($l->pivot->role_id)->description.' '.$l->shortname);
    }
    return $title->implode(', ');
  }
  public function getTeamMembershipsAttribute(): string
  {
    $teams = Team::whereIn('id', $this->memberships->pluck('membership_id'))->with(['league','club'])->without(['league.region','club.region'])->get();
    $title = collect();
    foreach ($teams as $t){
        $title->push($t->league->shortname .' MV '.($t->name ?? '?' ) );
    }
    return $title->implode(', ');
  }
  public function getMemberOfLeaguesAttribute(): string
  {
    return $this->leagues()->pluck('shortname')->unique()->implode(', ');
  }
  public function getMemberOfRegionAttribute(): string
  {
    return $this->region()->pluck('code')->unique()->implode('-');
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
