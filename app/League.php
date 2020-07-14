<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
  protected $fillable = [
        'id','name','shortname','region','active','above_region','changeable', 'schedule_id'
    ];

  public function region()
  {
      return $this->belongsTo('App\Region','region','id');
  }

  public function schedule()
  {
      return $this->belongsTo('App\Schedule','schedule_id','id');
  }

  public function clubs()
  {
      return $this->belongsToMany('App\Club','league_clubs')->withPivot('league_char', 'league_no');;
  }

  public function teams()
  {
      return $this->hasMany('App\Team');
  }

  public function member_roles()
  {
      return $this->morphMany(MemberRole::class, 'unit');
  }

  public function games()
  {
      return $this->hasMany('App\Game');
  }

}
