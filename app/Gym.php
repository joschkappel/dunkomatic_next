<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
  protected $fillable = [
        'id','name','gym_no','club_id','zip','street','city','directions'
    ];

  public function club()
  {
      return $this->belongsTo('App\Club');
  }    
}
