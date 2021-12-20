<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Enums\ReportFileType;
use App\Enums\Role;
use App\Models\Club;
use App\Models\League;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Schedule;
use App\Models\Team;
use App\Models\Gym;
use App\Models\Message;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Str;

class Region extends Model
{
    use HasFactory, CastsEnums;

    protected $fillable = [
        'id', 'code', 'name', 'hq', 'job_game_overlaps', 'game_slot',
        'job_game_notime', 'job_noleads', 'job_email_valid',
        'job_league_reports', 'job_club_reports', 'job_exports',
        'fmt_league_reports', 'fmt_club_reports',
        'close_assignment_at', 'close_registration_at',
        'close_selection_at', 'close_scheduling_at', 'close_referees_at'
    ];

    protected $casts = [
        'fmt_league_reports' => ReportFileType::class,
        'fmt_club_reports' => ReportFileType::class,
        'close_assignment_at' => 'date',
        'close_registration_at' => 'date',
        'close_selection_at' => 'date',
        'close_scheduling_at' => 'date',
        'close_referees_at' => 'date'
    ];

    public function clubs()
    {
        return $this->hasMany(Club::class);
    }

    public function teams()
    {
        return $this->hasManyThrough(Team::class, Club::class);
    }
    public function gyms()
    {
        return $this->hasManyThrough(Gym::class, Club::class);
    }

    public function leagues()
    {
        return $this->hasMany(League::class);
    }

    public function users()
    {
        // return $this->hasMany(User::class);
        $region = $this;
        return User::all()->filter( function ($user) use ($region) {
            return ($user->isNotAn('superadmin')) and ($user->can('access', $region));
        });
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function childRegions()
    {
        return $this->hasMany('App\Models\Region', 'hq', 'code');
    }
    public function parentRegion()
    {
        return $this->belongsTo('App\Models\Region', 'hq', 'code');
    }
    public function getIsTopLevelAttribute()
    {
        return ($this->childRegions->count() > 0);
    }
    public function getIsBaseLevelAttribute()
    {
        return ($this->childRegions->count() == 0);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function members()
    {
        return $this->morphToMany(Member::class, 'membership')->withPivot('role_id', 'function', 'id');
        // test: Club::find(261)->members()->withPivot('role_id','function')->get();
    }

    public function memberships()
    {
        return $this->morphMany(Membership::class, 'membership');
    }

    public function regionadmin()
    {
        return $this->members()->wherePivot('role_id', Role::RegionLead);
    }

    public function getClubFolderAttribute()
    {
        return  'exports/' . Str::of(config('global.season'))->replace('/', '_') . '/' . $this->code . '/' . config('dunkomatic.report_folder_clubs');
    }
    public function getLeagueFolderAttribute()
    {
        return  'exports/' . Str::of(config('global.season'))->replace('/', '_') . '/' . $this->code . '/' . config('dunkomatic.report_folder_leagues');
    }
    public function getTeamwareFolderAttribute()
    {
        return  'exports/' . Str::of(config('global.season'))->replace('/', '_') . '/' . $this->code . '/' . config('dunkomatic.report_folder_teamware');
    }

    public function getLeagueFilecountAttribute()
    {
        $directory = $this->league_folder;

        $reports = collect();
        foreach ($this->leagues as $league) {
            $shortname = $league->shortname;
            $reports = $reports->concat( collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname) {
                return (preg_match('(' . $shortname . ')', $value) === 1);
                //return (strpos($value,$llist[0]) !== false);
            }));
        }
        return count($reports);
    }
    public function getLeagueFilenamesAttribute()
    {
        $directory = $this->league_folder;

        $reports = collect();
        foreach ($this->leagues as $league) {
            $shortname = $this->shortname;
            $reports = $reports->concat( collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname) {
                return (preg_match('(' . $shortname . ')', $value) === 1);
                //return (strpos($value,$llist[0]) !== false);
            }));
        }
        return $reports;
    }
    public function getTeamwareFilecountAttribute()
    {
        $directory = $this->teamware_folder;

        $reports = collect();
        foreach ($this->leagues as $league) {
            $shortname = $league->shortname;
            $reports = $reports->concat( collect( Storage::allFiles($directory))->filter( function ($value, $key) use ($shortname) {
                                return ( preg_match('(' . $shortname . ')', $value) === 1);
                            }) );
        }
        return count($reports);
    }
    public function getTeamwareFilenamesAttribute()
    {
        $directory = $this->teamware_folder;

        $reports = collect();
        foreach ($this->leagues as $league) {
            $shortname = $this->shortname;
            $reports = $reports->concat( collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname) {
                return (preg_match('(' . $shortname . ')', $value) === 1);
                //return (strpos($value,$llist[0]) !== false);
            }));
        }
        return $reports;
    }
}
