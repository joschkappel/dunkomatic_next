<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\Auth;

class Club extends Model implements Auditable
{

  use \OwenIt\Auditing\Auditable;
  public function generateTags(): array
  {
      return [
          $this->shortname,
          $this->region
      ];
  }
  protected $fillable = [
        'id','name','shortname','region','url','club_no','club_ids','league_ids'
  ];

  /**
   * Get all of the users for the club.
   */
  public function useables()
  {
      return $this->morphToMany(User::class, 'useable');
  }

  public function gyms()
  {
      return $this->hasMany('App\Models\Gym');
  }

  public function leagues()
  {
      return $this->belongsToMany('App\Models\League','league_clubs')->withPivot('league_char', 'league_no');;
  }

  public function teams()
  {
      return $this->hasMany('App\Models\Team');
  }

  public function member_roles()
  {
      return $this->morphMany('App\Models\MemberRole', 'unit');
  }

  public function region()
  {
      return $this->belongsTo('App\Models\Region','region','code');
  }

  public function games_home()
  {
      return $this->hasMany('App\Models\Game', 'club_id_home', 'id');
  }
  public function games_home_notime()
  {
      return $this->hasMany('App\Models\Game', 'club_id_home', 'id');
  }
  public function games_home_noshow()
  {
      return $this->hasMany('App\Models\Game', 'club_id_home', 'id');
  }

  public function games_guest()
  {
      return $this->hasMany('App\Models\Game', 'club_id_guest', 'id');
  }
  public function scopeUserRegion($query)
  {
      return $query->where('region',Auth::user()->region);
  }
  public function scopeClubRegion($query, $region)
  {
      return $query->where('region', $region);
  }
}
