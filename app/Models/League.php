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
  /**
   * Get all of the users for the club.
   */
  public function useables()
  {
      return $this->morphToMany('App\Models\User', 'useable');
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

  public function member_roles()
  {
      return $this->morphMany(MemberRole::class, 'unit');
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
}
