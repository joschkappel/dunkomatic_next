<?php

namespace App\Models;

use App\Models\LeagueSizeChar;
use App\Models\LeagueSizeScheme;
use App\Models\Schedule;
use App\Models\League;

use Illuminate\Database\Eloquent\Model;

class LeagueSize extends Model
{

  const UNDEFINED = 1;

  /**
  * The table associated with the model.
  *
  * @var string
  */
  protected $primaryKey = 'id';
  public $incrementing = false;


  protected $fillable = [
      'size', 'iteration','description'
  ];

  public function chars()
  {
      return $this->hasMany(LeagueSizeChar::class);
  }
  public function schemes()
  {
      return $this->hasMany(LeagueSizeScheme::class);
  }
  public function schedules()
  {
      return $this->hasMany(Schedule::class);
  }
  public function leagues()
  {
      return $this->hasMany(League::class);
  }

}
