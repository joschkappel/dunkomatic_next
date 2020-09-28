<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeagueClub extends Model
{
  protected $fillable = [
        'id','league_id','club_id','league_char','league_no'
  ];

  public function league()
  {
      return $this->belongsTo('App\Models\League','league_id','id');
  }

  public function club()
  {
      return $this->belongsTo('App\Models\Club','club_id','id');
  }

}
