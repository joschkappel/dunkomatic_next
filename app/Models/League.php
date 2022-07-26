<?php

namespace App\Models;

use App\Enums\LeagueAgeType;
use App\Enums\LeagueColor;
use App\Enums\LeagueGenderType;
use App\Models\Game;
use App\Models\Region;
use App\Models\Club;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Schedule;
use App\Enums\LeagueState;
use App\Enums\ReportFileType;
use App\Enums\Role;
use App\Models\LeagueSize;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\League
 *
 * @property int $id
 * @property int $region_id
 * @property string $shortname
 * @property string $name
 * @property bool $above_region
 * @property int|null $league_size_id
 * @property int|null $schedule_id
 * @property mixed|null $age_type
 * @property mixed|null $gender_type
 * @property mixed $state
 * @property \Illuminate\Support\Carbon|null $assignment_closed_at
 * @property \Illuminate\Support\Carbon|null $registration_closed_at
 * @property \Illuminate\Support\Carbon|null $selection_opened_at
 * @property \Illuminate\Support\Carbon|null $selection_closed_at
 * @property \Illuminate\Support\Carbon|null $generated_at
 * @property \Illuminate\Support\Carbon|null $scheduling_closed_at
 * @property \Illuminate\Support\Carbon|null $referees_closed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Club[] $clubs
 * @property-read int|null $clubs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games
 * @property-read int|null $games_count
 * @property-read int|null $gcnt
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games_noreferee
 * @property-read int|null $games_noreferee_count
 * @property-read int|null $games_referee_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games_noshow
 * @property-read int|null $games_noshow_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games_notime
 * @property-read int|null $games_notime_count
 * @property-read int|null $games_time_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games_overlap
 * @property-read int|null $games_overlap_count
 * @property-read mixed $color
 * @property-read mixed $filecount
 * @property-read mixed $filenames
 * @property-read mixed $is_custom
 * @property-read mixed $size
 * @property-read mixed $state_count
 * @property-read LeagueSize|null $league_size
 * @property-read \Illuminate\Database\Eloquent\Collection|Member[] $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Membership[] $memberships
 * @property-read int|null $memberships_count
 * @property-read Region $region
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $registered_teams
 * @property-read int|null $registered_teams_count
 * @property-read Schedule|null $schedule
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $selected_teams
 * @property-read int|null $selected_teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 * @property-read int|null $chars_count
 * @method static \Database\Factories\LeagueFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|League newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|League newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|League query()
 * @method static \Illuminate\Database\Eloquent\Builder|League whereAboveRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereAgeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereAssignmentClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereGenderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereGeneratedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereLeagueSizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereRefereesClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereRegistrationClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereSchedulingClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereSelectionClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereSelectionOpenedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereShortname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class League extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable, hasFactory;

    protected $with = ['region'];

    public function generateTags(): array
    {
        return [
            $this->shortname,
            '('.$this->region->code.')'
        ];
    }
    protected $fillable = [
        'id', 'name', 'shortname', 'region_id', 'above_region', 'schedule_id', 'league_size_id',
        'generated_at', 'age_type', 'gender_type', 'state',
        'assignment_closed_at', 'registration_closed_at', 'selection_opened_at',
        'selection_closed_at', 'scheduling_closed_at', 'referees_closed_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'generated_at' => 'datetime',
        'registration_closed_at' => 'datetime',
        'assignment_closed_at' => 'datetime',
        'selection_opened_at' => 'datetime',
        'selection_closed_at' => 'datetime',
        'scheduling_closed_at' => 'datetime',
        'referees_closed_at' => 'datetime',
        'state' => LeagueState::class,
        'age_type' => LeagueAgeType::class,
        'gender_type' => LeagueGenderType::class,
        'above_region' => 'boolean',
    ];


    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function league_size(): BelongsTo
    {
        return $this->belongsTo(LeagueSize::class);
    }

    public function clubs(): BelongsToMany
    {
        return $this->belongsToMany(Club::class)->withPivot('league_char', 'league_no')->withTimestamps();
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
    public function users(): Collection
    {
        // get all users with access to this league
        $league = $this;
        return User::all()->filter( function ($user) use ($league) {
            return $user->can('access', $league);
        });
    }

    public function registered_teams(): HasMany
    {
        return $this->hasMany(Team::class)->whereNotNull('league_id');
    }
    public function selected_teams(): HasMany
    {
        return $this->hasMany(Team::class)->whereNotNull('league_no');
    }

    public function members(): MorphToMany
    {
        return $this->morphToMany(Member::class, 'membership')->withPivot('role_id', 'function', 'id');
        // test: League::find(251)->members()->withpivot('role_id')->get();
    }

    public function memberships(): MorphMany
    {
        return $this->morphMany(Membership::class, 'membership');
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function games_notime(): HasMany
    {
        return $this->hasMany(Game::class)->whereNull('game_time')->whereNotNull('club_id_home')->whereNotNull('club_id_guest');
    }
    public function games_noreferee(): HasMany
    {
        return $this->hasMany(Game::class)->whereNull('referee_1');
    }

    public function games_noshow(): HasMany
    {
        return $this->hasMany(Game::class)->whereNull('team_id_guest');
    }
    public function games_overlap(): HasMany
    {
        return $this->hasMany(Game::class);
    }
    public function memberIsA(Role $role): bool
    {
        return $this->members()->wherePivot('role_id', $role->value)->exists();
    }
    public function getColorAttribute(): string
    {
        // $league_colors[$this->above_region . $this->gender_type . $this->a
        return LeagueColor::coerce([ $this->above_region, $this->gender_type->value, $this->age_type->value  ])->key;
    }
    public function getSizeAttribute(): int
    {
        return $this->loadMissing('league_size')->league_size->size ?? null;
    }
    public function getIsCustomAttribute(): bool
    {
        return ($this->loadMissing('schedule','league_size')->schedule->league_size->size ?? 'undefined') == 0 ;
    }
    public function getIsNotReadyAttribute(): bool
    {
        return $this->loadMissing('schedule')->schedule == null;
    }
    public function getStateCountAttribute(): array
    {
        return [ 'assigned' => $this->loadCount('clubs')->clubs_count,
                    'registered' => $this->loadCount('teams')->teams_count,
                    'charspicked' => $this->loadCount(['teams as chars_count' => function ($q) { $q->whereNotNull('league_char'); }])->chars_count,
                    'generated' => $this->loadCount('games')->games_count,
                    'scheduled' => $this->loadCount(['games as games_time_count' => function ($q) { $q->whereNotNull('game_time'); }])->games_time_count,
                    'referees' => $this->loadCount(['games as games_referee_count' => function ($q) { $q->whereNotNull('referee_1'); }])->games_referee_count,
                    'size' => $this->loadMissing('league_size')->league_size->size ?? 0 ];
    }

    public function getFilecountAttribute(): int
    {
        $directory = $this->region->league_folder;
        $shortname = $this->shortname;
        $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname) {
            return (preg_match('(' . $shortname . ')', $value) === 1);
            //return (strpos($value,$llist[0]) !== false);
        });
        return count($reports);
    }
    public function filecount_for_type(ReportFileType $format): int
    {
        $directory = $this->region->league_folder;
        $shortname = $this->shortname;
        $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname, $format) {
            $sstr = "/(" . $shortname . ")(.*)(" . Str::lower($format->key) . ")/";
            return (preg_match($sstr, $value) === 1);
            //return (strpos($value,$llist[0]) !== false);
        });
        return count($reports);
    }
    public function getFilenamesAttribute(): Collection
    {
        $directory = $this->region->league_folder;
        $shortname = $this->shortname;

        $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname) {
            return (preg_match('(' . $shortname . ')', $value) === 1);
            //return (strpos($value,$llist[0]) !== false);
        });
        return $reports;
    }
    public function filenames_for_type(ReportFileType $format): Collection
    {
        $directory = $this->region->league_folder;
        $shortname = $this->shortname;

        $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname, $format) {
            return (preg_match('/(' . $shortname . ')(.*)('. Str::lower($format->key).')/', $value) === 1);
            //return (strpos($value,$llist[0]) !== false);
        });
        return $reports;
    }
}
