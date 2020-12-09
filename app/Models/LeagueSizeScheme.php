<?php

namespace App\Models;

use App\Models\LeagueSize;

use Illuminate\Database\Eloquent\Model;

class LeagueSizeScheme extends Model
{
  /**
  * The table associated with the model.
  *
  * @var string
  */

  public function league_size()
  {
      return $this->belongsTo(LeagueSize::class);
  }
}
