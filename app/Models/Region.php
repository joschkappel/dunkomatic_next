<?php

namespace App\Models;

use App\Enums\ReportFileType;
use App\Enums\Role;
use App\Traits\ReportManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * App\Models\Region
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $hq
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $job_game_overlaps
 * @property int $game_slot
 * @property int $job_game_notime
 * @property int $job_noleads
 * @property int $job_email_valid
 * @property mixed $fmt_league_reports
 * @property mixed $fmt_club_reports
 * @property \Illuminate\Support\Carbon|null $open_scheduling_at
 * @property \Illuminate\Support\Carbon|null $open_selection_at
 * @property \Illuminate\Support\Carbon|null $close_selection_at
 * @property \Illuminate\Support\Carbon|null $close_scheduling_at
 * @property \Illuminate\Support\Carbon|null $close_referees_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Region[] $childRegions
 * @property-read int|null $child_regions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Club[] $clubs
 * @property-read int|null $clubs_count
 * @property-read mixed $club_folder
 * @property-read mixed $is_base_level
 * @property-read mixed $is_top_level
 * @property-read int $league_filecount
 * @property-read mixed $league_filenames
 * @property-read mixed $league_folder
 * @property-read int $teamware_filecount
 * @property-read mixed $teamware_filenames
 * @property-read mixed $teamware_folder
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Member[] $regionadmins
 * @property-read int|null $regionadmins_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Gym[] $gyms
 * @property-read int|null $gyms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|League[] $leagues
 * @property-read int|null $leagues_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Member[] $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Membership[] $memberships
 * @property-read int|null $memberships_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Message[] $messages
 * @property-read int|null $messages_count
 * @property-read Region|null $parentRegion
 * @property-read \Illuminate\Database\Eloquent\Collection|Schedule[] $schedules
 * @property-read int|null $schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Team[] $teams
 * @property-read int|null $teams_count
 *
 * @method static \Database\Factories\RegionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereAutoStateChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCloseAssignmentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCloseRefereesAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCloseRegistrationAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCloseSchedulingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCloseSelectionAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereFmtClubReports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereFmtLeagueReports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereGameSlot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereHq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereJobEmailValid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereJobGameNotime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereJobGameOverlaps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereJobNoleads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Region extends Model
{
    use HasFactory, ReportManager;

    protected $with = ['childRegions'];

    protected $fillable = [
        'id', 'code', 'name', 'hq', 'job_game_overlaps', 'game_slot',
        'job_game_notime', 'job_noleads', 'job_email_valid',
        'fmt_league_reports', 'fmt_club_reports',
        'open_scheduling_at', 'open_selection_at',
        'close_selection_at', 'close_scheduling_at', 'close_referees_at',
    ];

    protected $casts = [
        'fmt_league_reports' => ReportFileType::class,
        'fmt_club_reports' => ReportFileType::class,
        'open_scheduling_at' => 'date',
        'open_selection_at' => 'date',
        'close_selection_at' => 'date',
        'close_scheduling_at' => 'date',
        'close_referees_at' => 'date',
    ];

    public function clubs(): HasMany
    {
        return $this->hasMany(Club::class);
    }

    public function teams(): HasManyThrough
    {
        return $this->hasManyThrough(Team::class, Club::class);
    }

    public function gyms(): HasManyThrough
    {
        return $this->hasManyThrough(Gym::class, Club::class);
    }

    public function leagues(): HasMany
    {
        return $this->hasMany(League::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function report_jobs(): HasMany
    {
        return $this->hasMany(ReportJob::class);
    }

    public function users(): Collection
    {
        // return $this->hasMany(User::class);
        $region = $this;

        return User::all()->filter(function ($user) use ($region) {
            return $user->isNotAn('superadmin') and (($user->can('access', $region)) or ($user->can('coaccess', $region)));
        });
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function childRegions(): HasMany
    {
        return $this->hasMany('App\Models\Region', 'hq', 'code');
    }

    public function parentRegion(): BelongsTo
    {
        return $this->belongsTo('App\Models\Region', 'hq', 'code');
    }

    public function getIsTopLevelAttribute(): bool
    {
        return $this->hq == null;
    }

    public function getIsBaseLevelAttribute(): bool
    {
        return $this->hq != null;
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function members(): MorphToMany
    {
        return $this->morphToMany(Member::class, 'membership')->withPivot('role_id', 'function', 'id');
        // test: Club::find(261)->members()->withPivot('role_id','function')->get();
    }

    public function memberships(): MorphMany
    {
        return $this->morphMany(Membership::class, 'membership');
    }

    public function regionadmins(): MorphToMany
    {
        return $this->morphToMany(Member::class, 'membership')->withPivot('role_id', 'function', 'id')->wherePivot('role_id', Role::RegionLead);
    }

    public function getClubFolderAttribute(): string
    {
        return  config('dunkomatic.folders.export').'/'.Str::of(config('global.season'))->replace('/', '_').'/'.$this->code.'/'.config('dunkomatic.export_folders.clubs');
    }

    public function getLeagueFolderAttribute(): string
    {
        return   config('dunkomatic.folders.export').'/'.Str::of(config('global.season'))->replace('/', '_').'/'.$this->code.'/'.config('dunkomatic.export_folders.leagues');
    }

    public function getArchiveFolderAttribute(): string
    {
        return   config('dunkomatic.folders.export').'/'.Str::of(config('global.season'))->replace('/', '_').'/'.$this->code.'/'.config('dunkomatic.export_folders.archive');
    }

    public function getTeamwareFolderAttribute(): string
    {
        return   config('dunkomatic.folders.export').'/'.Str::of(config('global.season'))->replace('/', '_').'/'.$this->code.'/'.config('dunkomatic.export_folders.teamware');
    }

    public function getRegionFolderAttribute(): string
    {
        return   config('dunkomatic.folders.export').'/'.Str::of(config('global.season'))->replace('/', '_').'/'.$this->code;
    }

    public function filecount(ReportFileType $format = null, string $fnprefix = null): int
    {
        $reports = $this->filenames($format, $fnprefix);

        return count($reports);
    }

    public function filenames(ReportFileType $format = null, string $fnprefix = null): Collection
    {
        if ($format == null) {
            $format = ReportFileType::coerce(ReportFileType::None);
        } else {
            $format = ReportFileType::coerce($format);
        }
        $fname = ($fnprefix == null) ? $this->code : $this->code.'_'.$fnprefix;

        // get main region folder files
        $reports = $this->get_reports($this->region_folder, $fname, $format);

        // add league folder files
        /*         $reports = $reports->concat(
                    $this->get_reports($this->region_folder.'/'.config('dunkomatic.export_folders.leagues'), null, $format )
                ); */

        // add teamware folder files
        /*         $reports = $reports->concat(
                    $this->get_reports($this->region_folder.'/'.config('dunkomatic.export_folders.teamware'), null,  ReportFileType::coerce(ReportFileType::CSV() ) )
                ); */
        // add top level region reports
        if ($this->is_base_level) {
            $fname = ($fnprefix == null) ? $this->parentRegion->code : $this->parentRegion->code.'_'.$fnprefix;
            $reports = $reports->concat(
                $this->get_reports($this->parentRegion->region_folder, $fname, $format)
            );
        }

        return $reports;
    }

    public function league_filecount(ReportFileType $format = null): int
    {
        $reports = $this->league_filenames($format);

        return count($reports);
    }

    public function league_filenames(ReportFileType $format = null): Collection
    {
        if ($format == null) {
            $format = ReportFileType::coerce(ReportFileType::None);
        } else {
            $format = ReportFileType::coerce($format);
        }

        $directory = $this->league_folder;
        $reports = $this->get_reports($directory, null, $format);

        return $reports;
    }

    public function teamware_filecount(): int
    {
        $reports = $this->teamware_filenames();

        return count($reports);
    }

    public function teamware_filenames(): Collection
    {
        $directory = $this->teamware_folder;
        $reports = $this->get_reports($directory, null, ReportFileType::coerce(ReportFileType::CSV()));

        return $reports;
    }
}
