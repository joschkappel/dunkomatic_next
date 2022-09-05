<?php

namespace Tests\Jobs;

use App\Jobs\GameOverlaps;
use App\Models\Region;
use App\Models\Game;
use App\Models\Club;
use App\Enums\Role;
use App\Notifications\ClubOverlappingGames;
use Tests\SysTestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;


class GameOverlapTest extends SysTestCase
{


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

        $region = Region::where('code','HBVDA')->first();

        // get a club that has games
        $clubs = $region->clubs->pluck('id');
        $club = Club::find( Game::whereIn('club_id_home', $clubs)->first()->club_id_home );
        $member = $club->members->where('pivot.role_id', Role::ClubLead)->first();
        Log::info('[EXPECT] overlap for',['clubid'=>$club->id]);

        // set home games to start at the saem time
        Game::where('club_id_home', $club->id)->update(['game_date'=>now(), 'game_time'=>'14:00:00']);

        $job_instance = resolve( GameOverlaps::class,['region'=>$region]);
        app()->call([$job_instance, 'handle']);

        Notification::assertSentTo($member, ClubOverlappingGames::class);
    }
}
