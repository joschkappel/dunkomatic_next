<?php

namespace Tests\Unit;

use App\Jobs\GameNotScheduled;
use App\Models\Region;
use App\Models\Game;
use App\Enums\Role;
use App\Notifications\ClubUnscheduledGames;
use Tests\SysTestCase;
use Illuminate\Support\Facades\Notification;


class GameUnscheduledTest extends SysTestCase
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

        $clubs = $region->clubs->pluck('id');
        $club = Game::whereIn('club_id_home', $clubs)->first()->club_home;
        $member = $club->members->where('pivot.role_id', Role::ClubLead)->first();

        Game::where('club_id_home', $club->id)->update(['game_time'=>null]);

        $job_instance = resolve( GameNotScheduled::class,['region'=>$region]);
        app()->call([$job_instance, 'handle']);

        Notification::assertSentTo($member, ClubUnscheduledGames::class);
    }
}