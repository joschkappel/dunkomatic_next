<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
  protected $fillable = [
        'id','league_id','region','game_no','game_plandate','game_date','game_time',
        'club_id_home','team_id_home','team_home','team_char_home',
        'club_id_guest','team_id_guest','team_guest','team_char_guest',
        'gym_no','gym_id','referee_1','referee_2'
  ];
  protected $dates = ['game_date', 'game_plandate'];

  public function region()
  {
      return $this->belongsTo('App\Region','region','code');
  }

  public function club_home()
  {
      return $this->belongsTo('App\Models\Club','club_id_home');
  }
  public function gym()
  {
      return $this->belongsTo('App\Models\Gym','gym_id');
  }

  public function club_guest()
  {
      return $this->belongsTo('App\Models\Club','club_id_guest');
  }

  public function league()
  {
      return $this->belongsTo('App\Models\League','league_id');
  }

  public function team_home()
  {
      return $this->belongsTo('App\Models\Team','team_id_home');
  }

  public function team_guest()
  {
      return $this->belongsTo('App\Models\Team','team_id_guest');
  }

  public function scopeNotime($query, $league)
  {
      return $query->where('league_id',$league)->whereNull('game_time');
  }

}
