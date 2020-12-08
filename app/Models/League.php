<?php

namespace App\Models;

use App\Models\Game;
use App\Models\User;
use App\Models\Region;
use App\Models\Club;
use App\Models\Member;
use App\Models\Membership;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        'id','name','shortname','region_id','active','above_region','schedule_id','generated_at','age_type','gender_type'
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
      return $this->belongsTo('App\Models\Schedule','schedule_id','id');
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
}
