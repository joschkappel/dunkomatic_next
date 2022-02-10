<?php

namespace App\Models;

use App\Models\LeagueSize;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LeagueSizeScheme
 *
 * @property int $id
 * @property int $league_size_id
 * @property int $game_day
 * @property int $game_no
 * @property string $team_home
 * @property string $team_guest
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read LeagueSize $league_size
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeScheme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeScheme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeScheme query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeScheme whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeScheme whereGameDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeScheme whereGameNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeScheme whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeScheme whereLeagueSizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeScheme whereTeamGuest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeScheme whereTeamHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeScheme whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
