<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
  protected $fillable = [
        'id','league_char','league_no','team_no','league_id','club_id','changeable', 'league_prev',
        'training_day', 'training_time', 'preferred_game_day', 'preferred_game_time',
        'coach_name', 'coach_phone1', 'coach_phone2', 'coach_email', 'shirt_color',
    ];

  public function club()
  {
      return $this->belongsTo('App\Models\Club');
  }

  public function league()
  {
      return $this->belongsTo('App\Models\League');
  }

  public function games_home()
  {
      return $this->hasMany('App\Models\Game','team_id_home');
  }

  public function games_guest()
  {
      return $this->hasMany('App\Models\Game','team_id_guest');
  }

}
