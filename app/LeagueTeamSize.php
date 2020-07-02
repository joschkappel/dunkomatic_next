<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeagueTeamSize extends Model
{

  /**
  * The table associated with the model.
  *
  * @var string
  */
  protected $table = 'league_team_sizes';

  protected $fillable = [
      'size', 'description'
  ];
}
