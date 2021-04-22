<?php

namespace App\Models;

use App\Models\Game;
use App\Models\User;
use App\Models\Region;
use App\Models\Club;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Schedule;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class League extends Model implements Auditable
{

  use \OwenIt\Auditing\Auditable, hasFactory;
  public function generateTags(): array
  {
      return [
          $this->shortname,
          $this->region
      ];
  }
  protected $fillable = [
        'id','name','shortname','region_id','above_region','schedule_id','generated_at','age_type','gender_type'
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
      // $cnt = League::find($this->id)->games()->count();
      //Log::debug(print_r($cnt,true));
      //return ( $cnt == 0 ) ? false : true;
      return ( $this->generated_at == null ) ? false : true;
  }

  public function region()
  {
      return $this->belongsTo(Region::class);
  }

  public function schedule()
  {
      return $this->belongsTo(Schedule::class);
  }

  public function clubs()
  {
      return $this->belongsToMany(Club::class)->withPivot('league_char', 'league_no')->withTimestamps();
  }

  public function teams()
  {
      return $this->hasMany('App\Models\Team');
  }

  public function members()
  {
      return $this->morphToMany(Member::class, 'membership')->withPivot('role_id','function','id');
      // test: League::find(251)->members()->withpivot('role_id')->get();
  }

  public function memberships()
  {
      return $this->morphMany(Membership::class, 'membership');
  }

  public function games()
  {
      return $this->hasMany(Game::class);
  }

  public function games_notime()
  {
      return $this->hasMany(Game::class)->whereNull('game_time');
  }

  public function games_noshow()
  {
      return $this->hasMany(Game::class)->whereNull('team_id_guest');
  }
  public function games_overlap()
  {
      return $this->hasMany(Game::class);
  }
  public function scopeUserRegion($query)
  {
      return $query->where('region_id',Auth::user()->region->id);
  }
  public function scopeIsGenerated($query)
  {
      return $query->whereNotNull('generated_at');
  }
  public function memberIsA($role_id)
  {
    return $this->members()->wherePivot('role_id', $role_id)->exists();
  }
  public function getSizeAttribute()
  {
    if (isset($this->schedule)){
        return $this->schedule->league_size->size;
    } else {
        return 0;
    }
  }
  public function getFilecountAttribute()
  {
    $directory = $this->region->league_folder;
    $shortname = $this->shortname;
    $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname) {
      return (preg_match('('.$shortname.')', $value) === 1);
      //return (strpos($value,$llist[0]) !== false);
    });
    return count($reports);
  }
  public function getFilenamesAttribute()
  {
    $directory = $this->region->league_folder;
    $shortname = $this->shortname;

    $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname) {
      return (preg_match('('.$shortname.')', $value) === 1);
      //return (strpos($value,$llist[0]) !== false);
    });
    return $reports;
  }
  public function getColorAttribute()
  {
      $league_colors = config('dunkomatic.league_colors');
      if (isset($league_colors[$this->above_region.$this->gender_type.$this->age_type])){
          return $league_colors[$this->above_region.$this->gender_type.$this->age_type];
      } else {
          return $league_colors[$this->above_region];
      }
  }
}
