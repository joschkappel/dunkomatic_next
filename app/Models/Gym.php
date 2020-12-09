<?php

namespace App\Models;

use App\Models\Club;
use App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
  protected $fillable = [
        'id','name','gym_no','club_id','zip','street','city','directions'
    ];

  public function club()
  {
      return $this->belongsTo(Club::class);
  }

  public function games()
  {
      return $this->hasMany(Game::class);
  }
}
