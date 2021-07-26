<?php

namespace App\Models;

use App\Models\Region;
use App\Models\League;
use App\Models\ScheduleEvent;
use App\Models\LeagueSize;
use App\Models\LeagueSizeScheme;
use App\Models\LeagueSizeChar;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
  use HasFactory;

  protected $fillable = [
        'id','name','region_id','eventcolor','league_size_id','custom_events'
    ];

  public static $createRules = [
      'name' => 'required',
      'region_id' => 'required|exists:regions,id',
      'eventcolor' => 'required',
      'league_size_id' => 'required_without:custom_events|exists:league_sizes,id',
  ];

  public static $updateRules = [
      'name' => 'required',
      'eventcolor' => 'required',
      'league_size_id' => 'required_without:custom_events|exists:league_sizes,id|notIn:'.LeagueSize::UNDEFINED,
  ];

  public function region()
  {
      return $this->belongsTo(Region::class);
  }

  public function leagues()
  {
      return $this->hasMany(League::class);
  }

  public function events()
  {
      return $this->hasMany(ScheduleEvent::class);
  }

  public function league_size()
  {
      return $this->belongsTo(LeagueSize::class);
  }

  public function schemes()
  {
    return $this->hasMany(LeagueSizeScheme::class, 'league_size_id','league_size_id' );
  }
  public function chars()
  {
    return $this->hasMany(LeagueSizeChar::class, 'league_size_id','league_size_id' );
  }
}
