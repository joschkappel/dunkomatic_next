<?php

namespace Tests\Feature\Jobs;

use App\Enums\Role;
use App\Jobs\GameNotScheduled;
use App\Models\Club;
use App\Models\Game;
use App\Models\League;
use App\Models\Region;
use App\Notifications\ClubUnscheduledGames;
use App\Traits\LeagueFSM;
use Illuminate\Support\Facades\Notification;
use Tests\SysTestCase;

class GameUnscheduledTest extends SysTestCase
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

        $clubs = $region->clubs->pluck('id');
        $game = Game::whereIn('club_id_home', $clubs)->first();
        $club = Club::find($game->club_id_home);
        $league = League::find($game->league_id);
        $this->open_game_scheduling($league);

        $member = $club->members->where('pivot.role_id', Role::ClubLead)->first();

        Game::where('club_id_home', $club->id)->update(['game_time' => null]);

        $job_instance = resolve(GameNotScheduled::class);
        app()->call([$job_instance, 'handle']);

        Notification::assertSentTo($member, ClubUnscheduledGames::class);
    }
}
