<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\LeagueSize
 *
 * @property int $id
 * @property int $size
 * @property string $description
 * @property-read \Illuminate\Database\Eloquent\Collection|LeagueSizeChar[] $chars
 * @property-read int|null $chars_count
 * @property-read \Illuminate\Database\Eloquent\Collection|League[] $leagues
 * @property-read int|null $leagues_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Schedule[] $schedules
 * @property-read int|null $schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection|LeagueSizeScheme[] $schemes
 * @property-read int|null $schemes_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSize query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSize whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeagueSize whereSize($value)
 * @mixin \Eloquent
 */
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
        'size', 'iteration', 'description',
    ];

    public function chars(): HasMany
    {
        return $this->hasMany(LeagueSizeChar::class);
    }

    public function schemes(): HasMany
    {
        return $this->hasMany(LeagueSizeScheme::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function leagues(): HasMany
    {
        return $this->hasMany(League::class);
    }
}
