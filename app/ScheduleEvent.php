<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleEvent extends Model
{
  protected $fillable = [
        'id','schedule_id','game_day','game_date','full_weekend'
    ];

  protected $dates = ['game_date'];

  public function schedule()
  {
      return $this->belongsTo('App\Schedule','schedule_id');
  }
}
