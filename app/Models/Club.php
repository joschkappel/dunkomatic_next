<?php

namespace App\Models;

use App\Models\Region;
use App\Models\Gym;
use App\Models\Team;
use App\Models\League;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Game;

use App\Rules\Uppercase;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Club extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory;

    public function generateTags(): array
    {
        return [
            $this->shortname,
            '('.$this->region->code.')'
        ];
    }
    protected $fillable = [
        'id', 'name', 'shortname', 'region_id', 'url', 'club_no'
    ];


    public function gyms()
    {
        return $this->hasMany(Gym::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function leagues()
    {
        return $this->belongsToMany(League::class)->withPivot('league_char', 'league_no')->withTimestamps();
    }

    public function memberships()
    {
        return $this->morphMany(Membership::class, 'membership');
    }

    public function members()
    {
        return $this->morphToMany(Member::class, 'membership')->withPivot('role_id', 'function');
        // test: Club::find(261)->members()->withPivot('role_id','function')->get();
    }
    public function users()
    {
        // get all users with access to this club
        $club = $this;
        return User::all()->filter( function ($user) use ($club) {
            return $user->can('access', $club);
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
    public function games_home()
    {
        return $this->hasMany(Game::class, 'club_id_home', 'id');
    }
    public function games_home_notime()
    {
        return $this->hasMany(Game::class, 'club_id_home', 'id')->whereNull('game_time');
    }
    public function games_home_noshow()
    {
        return $this->hasMany(Game::class, 'club_id_home', 'id')->whereNull('team_id_guest');
    }
    public function games_noreferee()
    {
        return $this->hasMany(Game::class, 'club_id_home', 'id')->whereNull('referee_1');

    }
    public function games_withreferee()
    {
        return $this->hasMany(Game::class, 'club_id_home', 'id')->whereNotNull('referee_1');

    }
    public function games_guest()
    {
        return $this->hasMany(Game::class, 'club_id_guest', 'id');
    }
    public function memberIsA($role_id)
    {
        return $this->members()->wherePivot('role_id', $role_id)->exists();
    }
    public function getFilecountAttribute()
    {
        $directory = $this->region->club_folder;
        $shortname = $this->shortname;
        $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname) {
            return (preg_match('(' . $shortname . ')', $value) === 1);
            //return (strpos($value,$llist[0]) !== false);
        });
        return count($reports);
    }
    public function getFilenamesAttribute()
    {
        $directory = $this->region->club_folder;
        $shortname = $this->shortname;

        $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($shortname) {
            return (preg_match('(' . $shortname . ')', $value) === 1);
            //return (strpos($value,$llist[0]) !== false);
        });
        return $reports;
    }
    public function scopeForRegion($query, $region)
    {
        return $query->where('region_id', $region->id);
    }
}
