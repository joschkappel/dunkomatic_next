<?php

namespace App\Models;

use App\Models\User;
use App\Models\Region;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

  public function memberships()
  {
      return $this->morphMany('App\Models\Membership', 'membershipable');
  }

  public function members()
  {
      return $this->morphToMany('App\Models\Member', 'membershipable', 'memberships', 'membershipable_id', 'member_id' )->withPivot('role_id','function','id');
      // test: Club::find(261)->members()->withPivot('role_id','function')->get();
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
  public function getFilecountAttribute()
  {
    $directory = Region::where('code',$this->region)->first()->club_folder;
    $shortname = $this->shortname;
    $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname) {
      return (preg_match('('.$shortname.')', $value) === 1);
      //return (strpos($value,$llist[0]) !== false);
    });
    return count($reports);
  }
  public function getFilenamesAttribute()
  {
    $directory = Region::where('code',$this->region)->first()->club_folder;
    $shortname = $this->shortname;

    $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname) {
      return (preg_match('('.$shortname.')', $value) === 1);
      //return (strpos($value,$llist[0]) !== false);
    });
    return $reports;
  }
}
