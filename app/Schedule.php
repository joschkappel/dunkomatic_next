<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{

  protected $fillable = [
        'id','name','region_id','eventcolor','active','size'
    ];
  protected $attributes = [
            'active' => false,
    ];

  public function region()
  {
      return $this->belongsTo('App\Region','region_id','id');
  }

  public function events()
  {
      return $this->hasMany('App\ScheduleEvent','schedule_id','id');
  }

  public function size()
  {
      return $this->belongsTo('App\LeagueTeamSize','size','size');
  }
}
