<?php

namespace App\Models;

use App\Models\Club;
use App\Models\Gym;
use App\Models\League;
use App\Models\Team;
use App\Models\Region;

use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property int $league_id
 * @property int $game_no
 * @property \Illuminate\Support\Carbon $game_plandate
 * @property \Illuminate\Support\Carbon $game_date
 * @property string|null $game_time
 * @property int|null $club_id_home
 * @property int|null $team_id_home
 * @property string|Team|null $team_home
 * @property string $team_char_home
 * @property int|null $club_id_guest
 * @property int|null $team_id_guest
 * @property string|Team|null $team_guest
 * @property string|null $team_guest
 * @property string $team_char_guest
 * @property string|null $gym_no
 * @property int|null $gym_id
 * @property string|null $referee_1
 * @property string|null $referee_2
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Club|null $club_guest
 * @property-read Club|null $club_home
 * @property-read Gym|null $gym
 * @property-read League $league
 * @property-read Region $region
 * @property-read int $gcnt
 * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereClubIdGuest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereClubIdHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereGameDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereGameNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereGamePlandate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereGameTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereGymId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereGymNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereLeagueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereReferee1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereReferee2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereTeamCharGuest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereTeamCharHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereTeamGuest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereTeamHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereTeamIdGuest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereTeamIdHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Game extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    public function generateTags(): array
    {
        return [
            '('.$this->region.')'
        ];
    }
    protected $fillable = [
        'id', 'league_id', 'region', 'game_no', 'game_plandate', 'game_date', 'game_time',
        'club_id_home', 'team_id_home', 'team_home', 'team_char_home',
        'club_id_guest', 'team_id_guest', 'team_guest', 'team_char_guest',
        'gym_no', 'gym_id', 'referee_1', 'referee_2'
    ];
    protected $dates = ['game_date', 'game_plandate'];

    public function region(): BelongsTo
    {
        return $this->belongsTo('App\Models\Region', 'region', 'code');
    }

    public function club_home(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id_home');
    }
    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function club_guest():BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id_guest');
    }

    public function league():BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function team_home(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id_home');
    }

    public function team_guest(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id_guest');
    }
    public function getRefereeAttribute(): string
    {
        $referee = '';
        if ( $this->referee_1 != null ) {
            $referee = $this->referee_1;
        }
        if ( $this->referee_2 != null ) {
            $referee .= $referee == '' ? $this->referee_2 : ' / '.$this->referee_2;
        }

       return $referee;
    }
/*     public function getTeamHomeAttribute(): string
    {

        if(!isset($this->relations['team_home'])) {

            $this->load('team_home');
        }
       return $this->relations['team_home']->name;
    } */
}
