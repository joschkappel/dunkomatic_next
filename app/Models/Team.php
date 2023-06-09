<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Team
 *
 * @property int $id
 * @property int $team_no
 * @property int|null $league_id
 * @property int $club_id
 * @property int|null $gym_id
 * @property string|null $league_char
 * @property int|null $league_no
 * @property string|null $preferred_league_char
 * @property int|null $preferred_league_no
 * @property string|null $league_prev
 * @property int|null $training_day
 * @property string|null $training_time
 * @property int|null $preferred_game_day
 * @property string|null $preferred_game_time
 * @property string|null $shirt_color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read Club $club
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games_guest
 * @property-read int|null $games_guest_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games_home
 * @property-read int|null $games_home_count
 * @property-read mixed $name
 * @property-read League|null $league
 *
 * @method static \Database\Factories\TeamFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereLeagueChar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereLeagueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereLeagueNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereLeaguePrev($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePreferredGameDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePreferredGameTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePreferredLeagueChar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePreferredLeagueNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereShirtColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereTeamNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereTrainingDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereTrainingTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Team extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, hasFactory;

    protected $with = ['club'];

    protected $appends = ['name','name_desc'];

    public function generateTags(): array
    {
        return [
            $this->id,
            $this->club->shortname,
            $this->league->shortname ?? '',
            '('.$this->club->region->code.')',
        ];
    }

    protected $fillable = [
        'id', 'league_char', 'league_no', 'team_no', 'league_id', 'club_id', 'gym_id', 'league_prev',
        'training_day', 'training_time', 'preferred_game_day', 'preferred_game_time',
        'shirt_color', 'preferred_league_char', 'preferred_league_no',
        'registered_at','charpicked_at','charreleased_at','withdrawn_at','withdrawn_from', 'charreleased'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'registered_at' => 'datetime',
        'charpicked_at' => 'datetime',
        'withdrawn_at' => 'datetime',
        'charreleased_at' => 'datetime',
    ];

    /**
     * Liusten to deleting events to remove some related entities
     */
    protected static function booted(): void
    {
        static::deleting(function (Team $team) {
            // delete potential home games
            $deleted = $team->games_home()->delete();
            if ($deleted) {
                Log::info('team home games deleted', ['team-id' => $team->id, 'count' => $deleted]);
            }

            // delete potential guest games
            $deleted = $team->games_guest()->delete();
            if ($deleted) {
                Log::info('team guest games deleted', ['team-id' => $team->id, 'count' => $deleted]);
            }

            // delete members with team membership
            $deleted = $team->memberships()->delete();
            Log::info('team memberships deleted', ['team-id' => $team->id, 'count' => $deleted]);
        });
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function members(): MorphToMany
    {
        return $this->morphToMany(Member::class, 'membership')->withPivot('role_id', 'function');
    }

    public function memberships(): MorphMany
    {
        return $this->morphMany(Membership::class, 'membership');
    }

    public function games_home(): HasMany
    {
        return $this->hasMany(Game::class, 'team_id_home');
    }

    public function games_guest(): HasMany
    {
        return $this->hasMany(Game::class, 'team_id_guest');
    }

    public function getNameAttribute(): string
    {
        $this->loadMissing('club');

        return $this->club->shortname.$this->team_no;
    }

    public function getNameDescAttribute(): string
    {
        $this->loadMissing('club','league');
        $name = $this->club->shortname.$this->team_no.' (';
        $league = $this->league()->exists() ? $this->league->shortname : $this->league_prev;
        $name .= ($league == null) ? $this->members->pluck('name')->implode(', ') : $league;
        $name .= ') ';

        return $name;
    }

    public function getStateAttribute(): array
    {
        $this->loadMissing('league');
        $league = $this->league()->exists() ? $this->league->shortname : '';
        $reg = $this->registered_at == null ? '' : $this->registered_at->locale( app()->getLocale() )->isoFormat('L LT');
        $reg = $reg == '' ? $league : $league.' ('.$reg.') ';

        $char = $this->charpicked_at == null ? '' : $this->charpicked_at->locale( app()->getLocale() )->isoFormat('L LT');
        $char = $char == '' ? $league.'-'.$this->league_no : $league.'-'.$this->league_no.' ('.$char.') ';

        $charrel = $this->charreleased_at == null ? '' : $this->charreleased.' ('.$this->charreleased_at->locale( app()->getLocale() )->isoFormat('L LT').') ';

        $withd = $this->withdrawn_at == null ? '' : $this->withdrawn_from.' ('.$this->withdrawn_at->locale( app()->getLocale() )->isoFormat('L LT').') ';

        return [ 'registered'=>$reg, 'charpicked' => $char, 'charreleased'=>$charrel,'withdrawn' => $withd ];
    }
}
