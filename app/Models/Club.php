<?php

namespace App\Models;

use App\Enums\Role;
use App\Models\Region;
use App\Models\Gym;
use App\Models\Team;
use App\Models\League;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Game;
use App\Enums\ReportFileType;
use App\Traits\ReportFinder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

/**
 * App\Models\Club
 *
 * @property int $id
 * @property int $region_id
 * @property string $shortname
 * @property string $name
 * @property string $club_no
 * @property boolean $inactive
 * @property string|null $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games_guest
 * @property-read int|null $games_guest_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games_home
 * @property-read int|null $games_home_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games_home_noshow
 * @property-read int|null $games_home_noshow_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games_home_notime
 * @property-read int|null $games_home_notime_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games_noreferee
 * @property-read int|null $games_noreferee_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games_withreferee
 * @property-read int|null $games_withreferee_count
 * @property-read mixed $filecount
 * @property-read mixed $filenames
 * @property-read \Illuminate\Database\Eloquent\Collection|Gym[] $gyms
 * @property-read int|null $gyms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|League[] $leagues
 * @property-read int|null $leagues_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Member[] $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Membership[] $memberships
 * @property-read int|null $memberships_count
 * @property-read Region $region
 * @property-read \Illuminate\Database\Eloquent\Collection|Team[] $registered_teams
 * @property-read int|null $registered_teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Team[] $selected_teams
 * @property-read int|null $selected_teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Team[] $teams
 * @property-read int|null $teams_count
 * @method static \Database\Factories\ClubFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Club forRegion($region)
 * @method static \Illuminate\Database\Eloquent\Builder|Club newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Club newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Club query()
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereClubNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereShortname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereUrl($value)
 * @mixin \Eloquent
 */
class Club extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, ReportFinder;

    protected $with = ['region'];

    public function generateTags(): array
    {
        return [
            $this->shortname,
            '('.$this->region->code.')'
        ];
    }
    protected $fillable = [
        'id', 'name', 'shortname', 'region_id', 'url', 'club_no', 'inactive'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'inactive' => 'boolean',
    ];

    public function gyms(): HasMany
    {
        return $this->hasMany(Gym::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function leagues(): BelongsToMany
    {
        return $this->belongsToMany(League::class)->withPivot('league_char', 'league_no')->withTimestamps();
    }

    public function memberships(): MorphMany
    {
        return $this->morphMany(Membership::class, 'membership');
    }

    public function members(): MorphToMany
    {
        return $this->morphToMany(Member::class, 'membership')->withPivot('role_id', 'function');
        // test: Club::find(261)->members()->withPivot('role_id','function')->get();
    }
    public function users(): Collection
    {
        // get all users with access to this club
        $users = DB::table('abilities')
        ->join('permissions', 'abilities.id', '=', 'permissions.ability_id')
        ->where('abilities.name','access')
        ->where('abilities.entity_type', Club::class)
        ->where('abilities.entity_id', $this->id)
        ->where('permissions.entity_type', User::class)
        ->select('permissions.entity_id')
        ->get();
        return User::whereIn('id', $users->pluck('entity_id'))->get();
    }
    public function getHasAdminUserAttribute(): bool
    {
        // get all users with access to this club
        $has_admin = false;
        $users = DB::table('abilities')
            ->join('permissions', 'abilities.id', '=', 'permissions.ability_id')
            ->where('abilities.name','access')
            ->where('abilities.entity_type', Club::class)
            ->where('abilities.entity_id', $this->id)
            ->where('permissions.entity_type', User::class)
            ->select('permissions.entity_id')
            ->get();
        foreach(User::whereIn('id', $users->pluck('entity_id'))->get() as $u){
            if ( $u->isAn('clubadmin') and $u->isNotAn('regionadmin')){
                $has_admin = true;
            }
        }

        return $has_admin;
    }
    public function registered_teams(): HasMany
    {
        return $this->hasMany(Team::class)->whereNotNull('league_id');
    }
    public function selected_teams(): HasMany
    {
        return $this->hasMany(Team::class)->whereNotNull('league_no');
    }

    public function games_home(): HasMany
    {
        return $this->hasMany(Game::class, 'club_id_home', 'id');
    }
    public function games_home_notime(): HasMany
    {
        return $this->hasMany(Game::class, 'club_id_home', 'id')->whereNull('game_time');
    }
    public function games_home_noshow(): HasMany
    {
        return $this->hasMany(Game::class, 'club_id_home', 'id')->whereNull('team_id_guest');
    }
    public function games_noreferee(): HasMany
    {
        return $this->hasMany(Game::class, 'club_id_home', 'id')->whereNull('referee_1');

    }
    public function games_withreferee(): HasMany
    {
        return $this->hasMany(Game::class, 'club_id_home', 'id')->whereNotNull('referee_1');

    }
    public function games_guest(): HasMany
    {
        return $this->hasMany(Game::class, 'club_id_guest', 'id');
    }
    public function memberIsA( Role $role): bool
    {
        return $this->members()->wherePivot('role_id', $role->value)->exists();
    }

    public function filecount(ReportFileType $format=null): int
    {
        $reports = $this->filenames($format);
        return count($reports);
    }

    public function filenames(ReportFileType $format=null): Collection
    {

        if ($format == null){
            $format = ReportFileType::coerce(ReportFileType::None);
        } else {
            $format = ReportFileType::coerce($format);
        }

        // get club reports
        $reports = $this->get_reports( $this->region->club_folder,$this->shortname, $format );

        // add region reports
        $reports = $reports->concat(
            $this->get_reports($this->region->region_folder, $this->region->code, $format)
        );
        // add above region reports
        if ($this->region->is_base_level){
            $reports = $reports->concat(
                $this->get_reports($this->region->parentRegion->region_folder, $this->region->parentRegion->code, $format)
            );
        }

        return $reports;
    }

    /**
     * Scope a query to only include active clubs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeActive($query)
    {
        $query->where('inactive', false);
    }
    /**
     * Scope a query to only include inactive clubs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeInactive($query)
    {
        $query->where('inactive', true);
    }

}
