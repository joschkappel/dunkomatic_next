<?php

namespace App\Models;

use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;
use App\Models\Game;
use App\Models\Region;
use App\Models\Club;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Schedule;
use App\Enums\LeagueState;
use App\Models\LeagueSize;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class League extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable, hasFactory;
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
    ];


    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function league_size()
    {
        return $this->belongsTo(LeagueSize::class);
    }

    public function clubs()
    {
        return $this->belongsToMany(Club::class)->withPivot('league_char', 'league_no')->withTimestamps();
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
    public function users()
    {
        // get all users with access to this league
        $league = $this;
        return User::all()->filter( function ($user) use ($league) {
            return $user->can('access', $league);
        });
    }

    public function registered_teams()
    {
        return $this->hasMany(Team::class)->whereNotNull('league_id');
    }
    public function selected_teams()
    {
        return $this->hasMany(Team::class)->whereNotNull('league_no');
    }

    public function members()
    {
        return $this->morphToMany(Member::class, 'membership')->withPivot('role_id', 'function', 'id');
        // test: League::find(251)->members()->withpivot('role_id')->get();
    }

    public function memberships()
    {
        return $this->morphMany(Membership::class, 'membership');
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function games_notime()
    {
        return $this->hasMany(Game::class)->whereNull('game_time');
    }
    public function games_noreferee()
    {
        return $this->hasMany(Game::class)->whereNull('referee_1');
    }

    public function games_noshow()
    {
        return $this->hasMany(Game::class)->whereNull('team_id_guest');
    }
    public function games_overlap()
    {
        return $this->hasMany(Game::class);
    }
    public function memberIsA($role_id)
    {
        return $this->members()->wherePivot('role_id', $role_id)->exists();
    }
    public function getSizeAttribute()
    {
        return isset($this->league_size->size) ? $this->league_size->size : null;
    }
    public function getStateCountAttribute()
    {
        if (isset($this->schedule)) {
            return [ 'assigned' => $this->clubs->count(),
                     'registered' => $this->teams->count(),
                     'charspicked' => $this->teams->whereNotNull('league_char')->count(),
                     'generated' => $this->games->count(),
                     'scheduled' => $this->games->whereNotNull('game_time')->count(),
                     'referees' => $this->games->whereNotNull('referee_1')->count(),
                     'size' => $this->schedule->league_size->size ];
        } else {
            return [ 'assigned' => $this->clubs->count(),
                     'registered' => $this->teams->count(),
                     'charspicked' => $this->teams->whereNotNull('league_char')->count(),
                     'generated' => $this->games->count(),
                     'scheduled' => $this->games->whereNotNull('game_time')->count(),
                     'referees' => $this->games->whereNotNull('referee_1')->count(),
                     'size' => 0 ];
        }
    }

    public function getFilecountAttribute()
    {
        $directory = $this->region->league_folder;
        $shortname = $this->shortname;
        $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname) {
            return (preg_match('(' . $shortname . ')', $value) === 1);
            //return (strpos($value,$llist[0]) !== false);
        });
        return count($reports);
    }
    public function getFilenamesAttribute()
    {
        $directory = $this->region->league_folder;
        $shortname = $this->shortname;

        $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname) {
            return (preg_match('(' . $shortname . ')', $value) === 1);
            //return (strpos($value,$llist[0]) !== false);
        });
        return $reports;
    }
    public function getColorAttribute()
    {
        $league_colors = config('dunkomatic.league_colors');
        if (isset($league_colors[$this->above_region . $this->gender_type . $this->age_type])) {
            return $league_colors[$this->above_region . $this->gender_type . $this->age_type];
        } else {
            return $league_colors[$this->above_region];
        }
    }
}
