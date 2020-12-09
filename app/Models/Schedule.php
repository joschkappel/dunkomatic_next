<?php

namespace App\Models;

use App\Models\Region;
use App\Models\League;
use App\Models\ScheduleEvent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
  use HasFactory;

  protected $fillable = [
        'id','name','region_id','eventcolor','active','size'
    ];
  protected $attributes = [
            'active' => false,
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

  public function size()
  {
      return $this->belongsTo('App\Models\LeagueTeamSize','size','size');
  }
}
