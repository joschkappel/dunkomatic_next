<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Club;
use App\Models\League;
use App\Models\Game;
use App\Models\Gym;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property string|null $coach_name
 * @property string|null $coach_phone1
 * @property string|null $coach_phone2
 * @property string|null $coach_email
 * @property int $changeable
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
 * @method static \Database\Factories\TeamFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereChangeable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCoachEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCoachName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCoachPhone1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCoachPhone2($value)
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

    // protected $with = ['club' /*, 'league'*/];
    protected $appends = ['name'];

    public function generateTags(): array
    {
        return [
            $this->id,
            $this->club->shortname,
            $this->league->shortname ?? '',
            '(' . $this->club->region->code . ')'
        ];
    }

    protected $fillable = [
        'id', 'league_char', 'league_no', 'team_no', 'league_id', 'club_id', 'gym_id',  'changeable', 'league_prev',
        'training_day', 'training_time', 'preferred_game_day', 'preferred_game_time',
        'coach_name', 'coach_phone1', 'coach_phone2', 'coach_email', 'shirt_color',
        'preferred_league_char', 'preferred_league_no',
    ];

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
        return $this->club->shortname . $this->team_no;
    }
    public function getNameDescAttribute(): string
    {
        $name = $this->club->shortname . $this->team_no .' (';
        $league = $this->league()->exists() ? $this->league->shortname : $this->league_prev;
        $name .= ($league == null) ? $this->coach_name : $league;
        $name .= ') ';
        return $name;
    }
}
