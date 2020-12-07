<?php

namespace App\Models;

use App\Models\Club;

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
}
