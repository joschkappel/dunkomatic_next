<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Enums\ReportFileType;

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
      'fmt_league_reports', 'fmt_club_reports'
  ];

  protected $casts = [
      'fmt_league_reports' => ReportFileType::class,
      'fmt_club_reports' => ReportFileType::class
  ];

  public function messages()
  {
      return $this->hasMany('App\Models\MessageDestination','region','code');
  }

  public function users()
  {
      return $this->hasMany('App\Models\User','region','code');
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
