<?php

namespace App\Models;

use App\Models\Club;
use App\Models\Game;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Gym
 *
 * @property int $id
 * @property int $club_id
 * @property string $gym_no
 * @property string $name
 * @property string $zip
 * @property string $city
 * @property string $street
 * @property string|null $directions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Club $club
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games
 * @property-read int|null $games_count
 * @property-read mixed $address
 * @method static \Database\Factories\GymFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gym newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gym query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereDirections($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereGymNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereZip($value)
 * @mixin \Eloquent
 */
class Gym extends Model
{
  use HasFactory;

  protected $fillable = [
        'id','name','gym_no','club_id','zip','street','city','directions'
    ];

  public function club(): BelongsTo
  {
      return $this->belongsTo(Club::class);
  }

  public function games(): HasMany
  {
      return $this->hasMany(Game::class);
  }
  public function getAddressAttribute(): string
  {
     return "{$this->street}, {$this->zip} {$this->city}";
  }
}
