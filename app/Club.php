<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
  protected $fillable = [
        'id','name','shortname','region','url','club_no','club_ids','league_ids'
    ];

  public function gyms()
  {
      return $this->hasMany('App\Gym');
  }

  public function teams()
  {
      return $this->hasMany('App\Team');
  }

  public function region()
  {
      return $this->belongsTo('App\Region','region','id');
  }
}
