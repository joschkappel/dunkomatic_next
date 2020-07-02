<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeagueClub extends Model
{
  protected $fillable = [
        'id','league_id','club_id','league_char','league_no'
  ];

  public function league()
  {
      return $this->belongsTo('App\League','league_id','id');
  }

  public function club()
  {
      return $this->belongsTo('App\Club','club_id','id');
  }

}
