<?php

namespace App\Models;

use App\Models\LeagueSize;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\LeagueSizeChar
 *
 * @property int $id
 * @property int $league_size_id
 * @property string $team_char
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read LeagueSize $league_size
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeChar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeChar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeChar query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeChar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeChar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeChar whereLeagueSizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeChar whereTeamChar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSizeChar whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LeagueSizeChar extends Model
{
  /**
  * The table associated with the model.
  *
  */

  public function league_size(): BelongsTo
  {
      return $this->belongsTo(LeagueSize::class);
  }
}
