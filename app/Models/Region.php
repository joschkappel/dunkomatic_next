<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Enums\ReportFileType;
use App\Enums\Role;
use App\Models\Club;
use App\Models\League;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Schedule;
use App\Models\Team;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Str;

class Region extends Model
{
  use HasFactory, CastsEnums;

  protected $fillable = [
      'id','code','name', 'hq', 'job_game_overlaps', 'game_slot',
      'job_game_notime', 'job_noleads', 'job_email_valid',
      'job_league_reports', 'job_club_reports','job_exports',
      'fmt_league_reports', 'fmt_club_reports', 'pickchar_enabled'
  ];

  protected $casts = [
      'fmt_league_reports' => ReportFileType::class,
      'fmt_club_reports' => ReportFileType::class
  ];

  public function clubs()
  {
    return $this->hasMany(Club::class);
  }

  public function teams()
  {
    return $this->hasManyThrough(Team::class, Club::class);
  }

  public function leagues()
  {
    return $this->hasMany(League::class);
  }

  public function users()
  {
      return $this->hasMany(User::class);
  }

  public function schedules()
  {
      return $this->hasMany(Schedule::class);
  }

  public function childRegions()
  {
      return $this->hasMany('App\Models\Region', 'hq', 'code');
  }
  public function parentRegion()
  {
      return $this->belongsTo('Region', 'hq', 'code');
  }
  public function getIsTopLevelAttribute()
  {
      return ($this->childRegions->count() > 0);
  }
  public function getIsBaseLevelAttribute()
  {
      return ($this->childRegions->count() == 0);
  }
  public function messages()
  {
      return $this->hasMany('App\Models\MessageDestination','region','code');
  }

  public function members()
  {
      return $this->morphToMany(Member::class, 'membership')->withPivot('role_id','function','id');
      // test: Club::find(261)->members()->withPivot('role_id','function')->get();
  }

  public function memberships()
  {
      return $this->morphMany(Membership::class, 'membership');
  }

  public function regionadmin()
  {
      return $this->members()->wherePivot('role_id', Role::RegionLead);
  }

  public function getClubFolderAttribute()
  {
    return  'exports/'.Str::of(config('global.season'))->replace('/','_').'/'.$this->code.'/'.config('dunkomatic.report_folder_clubs');
  }
  public function getLeagueFolderAttribute()
  {
    return  'exports/'.Str::of(config('global.season'))->replace('/','_').'/'.$this->code.'/'.config('dunkomatic.report_folder_leagues');
  }

}
