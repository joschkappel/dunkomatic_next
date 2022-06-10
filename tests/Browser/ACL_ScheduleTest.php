<?php

namespace Tests\Browser;

use App\Models\Region;
use App\Models\Member;
use App\Models\User;
use App\Models\Schedule;

use Silber\Bouncer\BouncerFacade as Bouncer;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;


class ACL_ScheduleTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected static $region;
    protected static $member;
    protected static $user;
    protected static $schedule;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);

        static::$region = Region::where('code','HBVDA')->first();
        static::$member = Member::factory()->create();
        static::$user = User::factory()->approved()->for(static::$member)->create();
        Bouncer::allow(static::$user)->to('access',static::$region);

        static::$schedule = Schedule::factory()->events(12)->create(['name' => 'testschedule']);
    }

    use withFaker;

    /**
     * @test
     * @group schedule
     * @group acl
     * @group superadmin
     */
    public function superadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'superadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_schedulelist($user);
    }

    /**
     * @test
     * @group schedule
     * @group acl
     * @group regionadmin
     */
    public function regionadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'regionadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_schedulelist($user);
    }

   /**
     * @test
     * @group schedule
     * @group acl
     * @group clubadmin
     */
    public function clubadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'clubadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_schedulelist($user);
    }

   /**
     * @test
     * @group schedule
     * @group acl
     * @group leagueadmin
     */
    public function leagueadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'leagueadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_schedulelist($user);
    }
   /**
     * @test
     * @group schedule
     * @group acl
     * @group guest
     */
    public function guest_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'guest')->to($user);
        Bouncer::refreshFor($user);

        $this->access_schedulelist($user);
    }

    /**
     * @test
     * @group schedule
     * @group acl
     * @group candidate
     */
    public function candidate_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'candidate')->to($user);
        Bouncer::refreshFor($user);

        $this->access_schedulelist($user);
    }


    private function access_schedulelist( $user )
    {
        $schedule = static::$schedule;

        $this->browse(function ($browser) use ($user, $schedule) {
            $browser->loginAs($user)->visitRoute('schedule.index',['language'=>'de','region'=>static::$region]);

            if ( $user->can('view-schedules') ) {
                $browser->assertRouteIs('schedule.index',['language'=>'de','region'=>static::$region]);
                ($user->can('create-schedules')) ? $browser->assertSee(__('schedule.action.create',$locale=['de'])) : $browser->assertDontSee(__('schedule.action.create',$locale=['de']));
                $browser->waitFor('.table');

                $events_count = $schedule->events->count() ?? '0';

                if ($user->canAny(['create-schedules', 'update-schedules'])) {
                    $browser->with('.table', function ($sRow) use ($schedule) {
                        $sRow->assertSeeLink($schedule->name);
                        $sRow->clickLink($schedule->name)
                                ->assertRouteIs('schedule.edit', ['language'=>'de','schedule'=>$schedule->id]);
                    });
                    $browser->assertPresent('@frmClose')->press('@frmClose')->waitFor('.table');

                    if ($user->can('update-schedules')){
                        $browser->with('.table', function ($sRow) use ($schedule, $events_count) {
                            $sRow->waitForLink($events_count);
                            $sRow->assertSeeLink($events_count);
                            $sRow->clickLink($events_count)
                                    ->assertRouteIs('schedule_event.list', ['schedule'=>$schedule->id]);
                        });
                    } else {
                        $browser->with('.table', function ($sRow) use ($events_count) {
                            $sRow->assertDontSeeLink($events_count)
                                 ->assertSee($events_count);

                        });
                    }
                } else {
                    $browser->with('.table', function ($sRow) use ($schedule, $events_count) {
                        $sRow->assertDontSeeLink($schedule->name)
                                ->assertSee($schedule->name)
                                ->assertDontSeeLink($events_count)
                                ->assertSee($events_count);
                    });
                }

            } else {
                $browser->assertSee('403');
            }
        });
    }


}
