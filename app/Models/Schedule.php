<?php

namespace App\Models;

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
      return $this->belongsTo('App\Models\Region','region_id','id');
  }

  public function events()
  {
      return $this->hasMany('App\Models\ScheduleEvent','schedule_id','id');
  }

  public function size()
  {
      return $this->belongsTo('App\Models\LeagueTeamSize','size','size');
  }
}
