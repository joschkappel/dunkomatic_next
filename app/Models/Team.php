<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Club;
use App\Models\League;
use App\Models\Game;

use App\Rules\GameMinute;
use App\Rules\GameHour;

class Team extends Model
{
  use HasFactory;

  protected $fillable = [
        'id','league_char','league_no','team_no','league_id','club_id','changeable', 'league_prev',
        'training_day', 'training_time', 'preferred_game_day', 'preferred_game_time',
        'coach_name', 'coach_phone1', 'coach_phone2', 'coach_email', 'shirt_color',
        'preferred_league_char','preferred_league_no',
    ];

  public static function getCreateRules()
  {
    return [
    'team_no' => 'required|integer|min:1|max:9',
    'training_day'   => 'required|integer|min:1|max:5',
    'training_time'  => array( 'required','date_format:H:i', new GameMinute, new GameHour),
    'preferred_game_day' => 'present|integer|min:1|max:7',
    'preferred_game_time' => array('required','date_format:H:i', new GameMinute, new GameHour),
    'coach_name'  => 'required|string|max:40',
    'coach_email' => 'present|email:rfc,dns',
    'coach_phone1' => 'present|string|max:20',
    'coach_phone2' => 'nullable|string|max:20',
    'league_prev' => 'nullable|string|max:20',
    'shirt_color' => 'required|string|max:20'
    ];
  }

  public static function getUpdateRules()
  { return [
    'team_no' => 'required|integer|min:1|max:9',
    'training_day'   => 'required|integer|min:1|max:5',
    'training_time'  => array('required','date_format:H:i', new GameMinute, new GameHour),
    'preferred_game_day' => 'present|integer|min:1|max:7',
    'preferred_game_time' => array('required','date_format:H:i', new GameMinute, new GameHour),
    'coach_name'  => 'required|string|max:40',
    'coach_email' => 'present|email:rfc,dns',
    'coach_phone1' => 'present|string|max:20',
    'coach_phone2' => 'nullable|string|max:20',
    'league_prev' => 'nullable|string|max:20',
    'shirt_color' => 'required|string|max:20'
    ];
  }

  public function club()
  {
      return $this->belongsTo(Club::class);
  }

  public function league()
  {
      return $this->belongsTo(League::class);
  }

  public function games_home()
  {
      return $this->hasMany(Game::class, 'team_id_home');
  }

  public function games_guest()
  {
      return $this->hasMany(Game::class,'team_id_guest');
  }

}
