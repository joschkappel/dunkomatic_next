<?php

namespace App\Models;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

class League extends Model implements Auditable
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
        'id','name','shortname','region','active','above_region','schedule_id','generated_at','age_type','gender_type'
  ];
  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
      'generated_at' => 'datetime',
  ];

  public function getIsGeneratedAttribute()
  {
      $cnt = League::find($this->id)->games()->count();
      //Log::debug(print_r($cnt,true));
      return ( $cnt == 0 ) ? false : true;
  }

  public function region()
  {
      return $this->belongsTo('App\Models\Region','region','code');
  }

  public function schedule()
  {
      return $this->belongsTo('App\Models\Schedule','schedule_id','id');
  }

  public function clubs()
  {
      return $this->belongsToMany('App\Models\Club','league_clubs')->withPivot('league_char', 'league_no');;
  }

  public function teams()
  {
      return $this->hasMany('App\Models\Team');
  }

  public function memberships()
  {
      return $this->morphMany('App\Models\Membership', 'membershipable');
  }

  public function members()
  {
      return $this->morphToMany('App\Models\Member', 'membershipable', 'memberships', 'membershipable_id', 'member_id' )->withPivot('role_id','function','id');
      // test: League::find(251)->members()->withpivot('role_id')->get();
  }

  public function games()
  {
      return $this->hasMany('App\Models\Game');
  }

  public function games_notime()
  {
      return $this->hasMany('App\Models\Game');
  }

  public function games_noshow()
  {
      return $this->hasMany('App\Models\Game');
  }
  public function games_overlap()
  {
      return $this->hasMany('App\Models\Game');
  }
  public function scopeUserRegion($query)
  {
      return $query->where('region',Auth::user()->region);
  }
  public function scopeLeagueRegion($query, $region)
  {
      return $query->where('region', $region);
  }
}
