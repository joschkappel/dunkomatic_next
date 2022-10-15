<?php

namespace Tests\Jobs;

use App\Enums\Role;
use App\Jobs\GameOverlaps;
use App\Models\Club;
use App\Models\Game;
use App\Models\League;
use App\Models\Region;
use App\Notifications\ClubOverlappingGames;
use App\Traits\LeagueFSM;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Tests\SysTestCase;

class GameOverlapTest extends SysTestCase
{
    use LeagueFSM;

    /**
     * run job
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_job()
    {
        Notification::fake();
        Notification::assertNothingSent();

        $region = Region::where('code', 'HBVDA')->first();

        // get a club that has games
        $clubs = $region->clubs->pluck('id');
        $game = Game::whereIn('club_id_home', $clubs)->first();
        $club = Club::find($game->club_id_home);
        $league = League::find($game->league_id);
        $this->open_game_scheduling($league);
        $member = $club->members->where('pivot.role_id', Role::ClubLead)->first();
        Log::info('[EXPECT] overlap for', ['clubid' => $club->id]);

        // set home games to start at the saem time

        Game::where('club_id_home', $club->id)->update(['game_date' => now(), 'game_time' => '14:00:00']);

        $job_instance = resolve(GameOverlaps::class);
        app()->call([$job_instance, 'handle']);

        Notification::assertSentTo($member, ClubOverlappingGames::class);
    }
}
