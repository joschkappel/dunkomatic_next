<?php

namespace App\Models;

use App\Enums\ScheduleColor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Schedule
 *
 * @property int $id
 * @property string $name
 * @property int $region_id
 * @property int|null $league_size_id
 * @property int $iterations
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $custom_events
 * @property-read \Illuminate\Database\Eloquent\Collection|LeagueSizeChar[] $chars
 * @property-read int|null $chars_count
 * @property-read \Illuminate\Database\Eloquent\Collection|ScheduleEvent[] $events
 * @property-read int|null $events_count
 * @property-read mixed $color
 * @property-read LeagueSize|null $league_size
 * @property-read \Illuminate\Database\Eloquent\Collection|League[] $leagues
 * @property-read int|null $leagues_count
 * @property-read Region $region
 * @property-read \Illuminate\Database\Eloquent\Collection|LeagueSizeScheme[] $schemes
 * @property-read int|null $schemes_count
 *
 * @method static \Database\Factories\ScheduleFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereCustomEvents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereIterations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereLeagueSizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Schedule extends Model
{
    use HasFactory;

    protected $with = ['region'];

    protected $fillable = [
        'id', 'name', 'region_id', 'league_size_id', 'custom_events', 'iterations',
        'active', 'note_homegames', 'note_2'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'custom_evens' => 'boolean',
        'active' => 'boolean'
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function leagues(): HasMany
    {
        return $this->hasMany(League::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(ScheduleEvent::class);
    }

    public function league_size(): BelongsTo
    {
        return $this->belongsTo(LeagueSize::class);
    }

    public function schemes(): HasMany
    {
        return $this->hasMany(LeagueSizeScheme::class, 'league_size_id', 'league_size_id');
    }

    public function chars(): HasMany
    {
        return $this->hasMany(LeagueSizeChar::class, 'league_size_id', 'league_size_id');
    }

    public function getColorAttribute(): string
    {
        Log::debug('Searching schedule color key', ['key' => [$this->region->is_top_level, $this->league_size->size, $this->iterations]]);
        if ($this->league_size->size == 0) {
            return ScheduleColor::coerce([$this->region->is_top_level, 0, '*'])->key;
        } else {
            return ScheduleColor::coerce([$this->region->is_top_level, $this->league_size->size, $this->iterations])->key;
        }
    }

    public function getMaxEventsAttribute(): int
    {
        $size = $this->league_size->size ?? 0;
        if ($size == 0) {
            return 0;
        } else {
            $repeat = $this->iterations ?? 0;

            return ($size - 1) * 2 * $repeat;
        }
    }

    /**
     * Scope a query to only include active schedules.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('active', 1);
    }
}
