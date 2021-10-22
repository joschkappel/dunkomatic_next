<?php

namespace Tests\Browser;

use App\Models\Region;
use App\Models\User;
use App\Models\Club;
use App\Models\Member;
use App\Enums\LeagueState;

use Bouncer;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;


class ACL_ClubTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected static $club;
    protected static $gym;
    protected static $team;
    protected static $member;
    protected static $user;
    protected static $region;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
        static::$club = Club::factory()->hasAttached( Member::factory()->count(3),['role_id'=>2])->hasTeams(2)->hasGyms(1)->create(['name'=>'testclub']);
        static::$team = static::$club->teams->first();
        static::$gym = static::$club->gyms->first();
        static::$member = static::$club->members->first();

        static::$region = Region::where('code','HBVDA')->first();
        $member = Member::factory()->create();
        static::$user = User::factory()->approved()->for(static::$region)->for($member)->create();

    }

    use withFaker;

    /**
     * @test
     * @group club
     * @group acl
     * @group superadmin
     */
    public function superadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'superadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_clublist($user);
        $this->access_clubdashboard($user);
    }

    /**
     * @test
     * @group club
     * @group acl
     * @group regionadmin
     */
    public function regionadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'regionadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_clublist($user);

        Bouncer::allow($user)->to('manage', static::$club );
        Bouncer::refreshFor($user);
        $this->access_clublist($user);

        $this->access_clubdashboard($user);
    }

    /**
     * @test
     * @group club
     * @group acl
     * @group regionobserver
     */
    public function regionobserver_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'regionobserver')->to($user);
        Bouncer::refreshFor($user);

        $this->access_clublist($user);

        Bouncer::allow($user)->to('manage', static::$club );
        Bouncer::refreshFor($user);
        $this->access_clublist($user);

        $this->access_clubdashboard($user);
    }

   /**
     * @test
     * @group club
     * @group acl
     * @group clubadmin
     */
    public function clubadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'clubadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_clublist($user);

        Bouncer::allow($user)->to('manage', static::$club );
        Bouncer::refreshFor($user);
        $this->access_clublist($user);

        $this->access_clubdashboard($user);
    }

   /**
     * @test
     * @group club
     * @group acl
     * @group clubobserver
     */
    public function clubobserver_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'clubobserver')->to($user);
        Bouncer::refreshFor($user);

        $this->access_clublist($user);

        Bouncer::allow($user)->to('manage', static::$club );
        Bouncer::refreshFor($user);
        $this->access_clublist($user);

        $this->access_clubdashboard($user);

    }
   /**
     * @test
     * @group club
     * @group acl
     * @group leagueadmin
     */
    public function leagueadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'leagueadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_clublist($user);

        Bouncer::allow($user)->to('manage', static::$club );
        Bouncer::refreshFor($user);
        $this->access_clublist($user);

        $this->access_clubdashboard($user);

    }
   /**
     * @test
     * @group club
     * @group acl
     * @group leagueobserver
     */
    public function leagueobserver_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'leagueobserver')->to($user);
        Bouncer::refreshFor($user);

        $this->access_clublist($user);

        Bouncer::allow($user)->to('manage', static::$club );
        Bouncer::refreshFor($user);
        $this->access_clublist($user);

        $this->access_clubdashboard($user);

    }
   /**
     * @test
     * @group club
     * @group acl
     * @group guest
     */
    public function guest_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'guest')->to($user);
        Bouncer::refreshFor($user);

        $this->access_clublist($user);
        $this->access_clubdashboard($user);

    }
   /**
     * @test
     * @group club
     * @group acl
     * @group candidate
     */
    public function candidate_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'candidate')->to($user);
        Bouncer::refreshFor($user);

        $this->access_clublist($user);
        $this->access_clubdashboard($user);

    }

    private function access_clublist( $user )
    {
        $club = static::$club;
        $region = static::$region;

        $this->browse(function ($browser) use ($user, $club, $region) {
            $browser->loginAs($user)->visitRoute('club.index',['language'=>'de', 'region'=>$region]);

            if ( $user->can('view-clubs') ) {
                $browser->assertRouteIs('club.index',['language'=>'de','region'=>$region]);
                ($user->can('create-clubs')) ? $browser->assertSee(__('club.action.create',$locale=['de'])) : $browser->assertDontSee(__('club.action.create',$locale=['de']));
                $browser->waitFor('.table')->assertSeeLink($club->shortname)->clickLink($club->shortname);
                (  ($user->can('manage', $club)) or  ($user->canAny(['create-clubs', 'update-clubs'])) ) ? $browser->assertRouteIs('club.dashboard', ['language'=>'de','club'=>$club->id]) :  $browser->assertRouteIs('club.briefing', ['language'=>'de','club'=>$club->id]);
            } else {
                $browser->assertSee('403');
            }
        });
    }

    private function access_clubdashboard( $user)
    {
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)->visitRoute('club.dashboard',['language'=>'de', 'club'=>static::$club]); // ->screenshot($user->getRoles()[0]);
            $team = static::$team;
            $club = static::$club;
            $gym = static::$gym;
            $member = static::$member;

            if (($user->can('manage', $club)) or  ( $user->canAny(['create-clubs', 'update-clubs'])) ){
                ($user->can('update-clubs')) ? $browser->assertSee(__('club.action.edit',$locale=['de'])) : $browser->assertDontSee(__('club.action.edit',$locale=['de']));
                ($user->can('create-clubs')) ? $browser->assertSee(__('club.action.delete',$locale=['de'])) : $browser->assertDontSee(__('club.action.delete',$locale=['de']));
                ($user->can('create-members')) ? $browser->assertSee(__('club.member.action.create',$locale=['de'])) : $browser->assertDontSee(__('club.member.action.create',$locale=['de']));
                ($user->can('create-gyms')) ? $browser->assertSee(__('gym.action.create',$locale=['de'])) : $browser->assertDontSee(__('gym.action.create',$locale=['de']));
                ($user->can('create-teams')) ? $browser->assertSee(__('team.action.create',$locale=['de'])) : $browser->assertDontSee(__('team.action.create',$locale=['de']));

                $browser->with('#teamsCard', function ($teamCard) use ($user, $club, $team) {
                    $teamCard->click('.btn-tool')->waitFor('.btn-tool');
                    ($user->can('create-teams')) ? $teamCard->assertButtonEnabled('#deleteTeam') :  $teamCard->assertButtonDisabled('#deleteTeam');
                    ($user->can('update-teams')) ? $teamCard->assertSeeLink($club->shortname.$team->team_no) : $teamCard->assertDontSeeLink($club->shortname.$team->team_no);
                    ($user->can('update-teams')) ? $teamCard->assertPresent('#assignLeague') : $teamCard->assertNotPresent('#assignLeague');
                    ( ($user->can('update-teams')) and ($club->leagues->where('state', LeagueState::Selection())->count() > 0)) ? $teamCard->assertSeeLink(__('team.action.plan.season'))  : $teamCard->assertDontSeeLink(__('team.action.plan.season'));
                    ( ($user->can('update-teams')) and ($club->leagues->where('state', LeagueState::Selection())->count() > 0)) ? $teamCard->assertSeeLink(__('team.action.pickchars'))  : $teamCard->assertDontSeeLink(__('team.action.pickchars'));
                });
                $browser->with('#gymsCard', function ($gymCard) use ($user, $club, $gym) {
                    $gymCard->click('.btn-tool')->waitFor('.btn-tool');
                    ($user->can('create-gyms')) ? $gymCard->assertButtonEnabled('#deleteGym') :  $gymCard->assertButtonDisabled('#deleteGym');
                    ($user->can('update-gyms')) ? $gymCard->assertSeeLink($gym->gym_no.' - '.$gym->name) : $gymCard->assertDontSeeLink($gym->gym_no.' - '.$gym->name);
                });
                $browser->with('#membersCard', function ($memberCard) use ($user, $club, $member) {
                    $memberCard->click('.btn-tool')->waitFor('.btn-tool');
                    ($user->can('create-members')) ? $memberCard->assertButtonEnabled('#deleteMember') :  $memberCard->assertButtonDisabled('#deleteMember');
                    ($user->can('update-members')) ? $memberCard->assertSeeLink($member->name) : $memberCard->assertDontSeeLink($member->name);
                    ($user->can('update-members')) ? $memberCard->assertSeeLink(__('role.send.invite')) : $memberCard->assertDontSeeLink(__('role.send.invite'));
                    ($user->can('update-members')) ? $memberCard->assertButtonEnabled('#addMembership') : $memberCard->assertButtonDisabled('#addMembership');
                    ($user->can('update-members')) ? $memberCard->assertButtonEnabled('#modMembership') : $memberCard->assertButtonDisabled('#modMembership');
                });
                $browser->with('#gamesCard', function ($gameCard) use ($user) {
                    $gameCard->click('.btn-tool')->waitFor('.btn-tool');
                    ($user->can('update-games')) ? $gameCard->assertSeeLink(__('club.action.edit-homegame')) : $gameCard->assertDontSeeLink(__('club.action.edit-homegame')) ;
                    ($user->can('view-games')) ? $gameCard->assertSeeLink(__('club.action.chart-homegame')) : $gameCard->assertDontSeeLink(__('club.action.chart-homegame'));
                });
            } else {
                $browser->assertSee('403');
            }
        });

    }

}