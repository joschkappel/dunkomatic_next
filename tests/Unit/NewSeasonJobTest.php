<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Notification;

use App\Jobs\ProcessNewSeason;
use App\Enums\LeagueState;
use App\Enums\Role;
use App\Notifications\CheckRegionSettings;
use App\Notifications\NewSeason;
use App\Models\User;
use App\Models\Member;
use App\Models\Membership;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;



class NewSeasonJobTest extends TestCase
{
/*     public function setUp(): void
    {
        parent::setUp();
        // seed the database
        Artisan::call('migrate:fresh --seed');
        Log::info('TestDB seeded');
    } */

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
        Queue::fake();

        Notification::fake();
        Notification::assertNothingSent();

        $this->travel(1)->years();
        $job_instance = resolve(ProcessNewSeason::class);
        app()->call([$job_instance, 'handle']);

        // check that teams are reset
        // $this->assertDatabaseHas('teams', ['league_no' => null,'league_id'=>null ]);
        // check that gaems are truncated
        $this->assertDatabaseCount('games', 0);
        // check that league status is reset
        // $this->assertDatabaseHas('leagues', ['state' => LeagueState::Assignment()  ]);

        Notification::assertSentTo( [Membership::where('role_id', Role::RegionLead() )->first()->member], CheckRegionSettings::class );
        Notification::assertSentTo( User::whereNotNull('approved_at')->whereNotNull('email_verified_at')->get(), NewSeason::class );
        Notification::assertSentTo( Member::all(), NewSeason::class );

        Queue::assertNothingPushed();
        $this->travelBack();

    }
}
