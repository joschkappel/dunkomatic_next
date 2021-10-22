<?php

namespace Tests\Browser;

use App\Models\Region;
use App\Models\Member;
use App\Models\Club;
use App\Models\League;
use App\Models\User;
use App\Traits\LeagueFSM;
use App\Enums\LeagueState;
use Bouncer;

use Illuminate\Support\Facades\Log;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;


class ACL_LeagueTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected static $league;
    protected static $club_a;
    protected static $club_r;
    protected static $team;
    protected static $member;
    protected static $user;
    protected static $region;

    use LeagueFSM;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);

        static::$club_a = Club::factory()->hasAttached( Member::factory()->count(1),['role_id'=>1])->hasTeams(2)->create(['name'=>'testclub_A']);
        static::$club_r = Club::factory()->hasAttached( Member::factory()->count(1),['role_id'=>1])->hasTeams(2)->create(['name'=>'testclub_R']);
        static::$team = static::$club_r->teams->first();

        static::$league = League::factory()->hasAttached( Member::factory()->count(1),['role_id'=>3])->create(['name'=>'testleague']);
        static::$member = static::$league->members->first();

        // assign club_A
        static::$league->clubs()->attach( [
            static::$club_a->id =>  ['league_no'=>1,'league_char'=>'A'],
            static::$club_r->id =>  ['league_no'=>2,'league_char'=>'B']
        ]);

        // register clbu_B
        static::$team->league()->associate(static::$league)->save();

        //set status to registration
        $this->open_assignment(static::$league);
        $this->close_assignment(static::$league);

        static::$region = Region::where('code','HBVDA')->first();
        $member = Member::factory()->create();
        static::$user = User::factory()->approved()->for(static::$region)->for($member)->create();
/*         Bouncer::allow(static::$user)->to('manage', static::$league );
        Bouncer::refreshFor(static::$user); */

    }

    use withFaker;

    /**
     * @test
     * @group league
     * @group acl
     * @group superadmin
     */
    public function superadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'superadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);
        $this->access_leaguedashboard($user);
    }

    /**
     * @test
     * @group league
     * @group acl
     * @group regionadmin
     */
    public function regionadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'regionadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);

        Bouncer::allow($user)->to('manage', static::$league );
        Bouncer::refreshFor($user);
        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);

        $this->access_leaguedashboard($user);
    }

    /**
     * @test
     * @group league
     * @group acl
     * @group regionobserver
     */
    public function regionobserver_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'regionobserver')->to($user);
        Bouncer::refreshFor($user);

        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);

        Bouncer::allow($user)->to('manage', static::$league );
        Bouncer::refreshFor($user);
        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);

        $this->access_leaguedashboard($user);
    }

   /**
     * @test
     * @group league
     * @group acl
     * @group clubadmin
     */
    public function clubadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'clubadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);

        Bouncer::allow($user)->to('manage', static::$league );
        Bouncer::refreshFor($user);
        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);

        $this->access_leaguedashboard($user);

    }

   /**
     * @test
     * @group league
     * @group acl
     * @group clubobserver
     */
    public function clubobserver_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'clubobserver')->to($user);
        Bouncer::refreshFor($user);

        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);

        Bouncer::allow($user)->to('manage', static::$league );
        Bouncer::refreshFor($user);
        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);

        $this->access_leaguedashboard($user);

    }
   /**
     * @test
     * @group league
     * @group acl
     * @group leagueadmin
     */
    public function leagueadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'leagueadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);

        Bouncer::allow($user)->to('manage', static::$league );
        Bouncer::refreshFor($user);
        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);

        $this->access_leaguedashboard($user);

    }
     /**
     * @test
     * @group league
     * @group acl
     * @group leagueobserver
     */
    public function leagueobserver_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'leagueobserver')->to($user);
        Bouncer::refreshFor($user);

        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);

        Bouncer::allow($user)->to('manage', static::$league );
        Bouncer::refreshFor($user);
        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);

        $this->access_leaguedashboard($user);

    }
   /**
     * @test
     * @group league
     * @group acl
     * @group guest
     */
    public function guest_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'guest')->to($user);
        Bouncer::refreshFor($user);

        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);
        $this->access_leaguedashboard($user);

    }
   /**
     * @test
     * @group league
     * @group acl
     * @group candidate
     */
    public function candidate_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'candidate')->to($user);
        Bouncer::refreshFor($user);

        $this->access_leaguemgmtlist($user);
        $this->access_leaguelist($user);
        $this->access_leaguedashboard($user);;

    }

    private function access_leaguemgmtlist( $user )
    {
        $league = static::$league;

        $this->browse(function ($browser) use ($user, $league) {
            $browser->loginAs($user)->visitRoute('league.index_mgmt',['language'=>'de']);

            if ( $user->canAny(['create-leagues','update-leagues']) ) {
                $browser->assertRouteIs('league.index_mgmt',['language'=>'de']);
                ($user->can('create-leagues')) ? $browser->assertSee(__('league.action.create',$locale=['de'])) : $browser->assertDontSee(__('league.action.create',$locale=['de']));
                $browser->waitFor('.table')->assertSeeLink($league->shortname)->clickLink($league->shortname)->assertRouteIs('league.dashboard', ['language'=>'de','league'=>$league->id]);
            } else {
                $browser->assertSee('403');
            }
        });
    }

    private function access_leaguelist( $user )
    {
        $league = static::$league;
        $region = static::$region;

        $this->browse(function ($browser) use ($user, $league, $region) {
            $browser->loginAs($user)->visitRoute('league.index',['language'=>'de', 'region'=>$region]);

            if ( $user->can('view-leagues') ) {
                $browser->assertRouteIs('league.index',['language'=>'de','region'=>$region]);
                $browser->waitFor('.table')->assertSeeLink($league->shortname)->clickLink($league->shortname);
                (  ($user->can('manage', $league)) or  ($user->canAny(['create-leagues', 'update-leagues'])) ) ? $browser->assertRouteIs('league.dashboard', ['language'=>'de','league'=>$league->id]) :  $browser->assertRouteIs('league.briefing', ['language'=>'de','league'=>$league->id]);
            } else {
                $browser->assertSee('403');
            }
        });
    }

    private function access_leaguedashboard( $user)
    {
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)->visitRoute('league.dashboard',['language'=>'de', 'league'=>static::$league]); // ->screenshot($user->getRoles()[0]);
            $member = static::$member;
            $league = static::$league;
            // $browser->storeSource($user->getRoles()[0].'_leaguedashboard');

            if (($user->can('manage', $league)) or  ( $user->canAny(['create-leagues', 'update-leagues'])) ){
                ($user->can('update-leagues')) ? $browser->assertSee(__('league.action.edit',$locale=['de'])) : $browser->assertDontSee(__('league.action.edit',$locale=['de']));
                ($user->can('create-leagues')) ? $browser->assertSee(__('league.action.delete',$locale=['de'])) : $browser->assertDontSee(__('league.action.delete',$locale=['de']));
                ($user->can('create-members')) ? $browser->assertSee(__('league.member.action.create',$locale=['de'])) : $browser->assertDontSee(__('league.member.action.create',$locale=['de']));

                $browser->with('#clubsCard', function ($clubsCard) use ($user, $league) {
                    $clubsCard->with('#clubsCardFooter', function ($clubFooter) use ($user, $league) {
                        ( ($user->cannot('update-teams'))  or ($league->state_count['registered'] == 0) ) ? $clubFooter->assertButtonDisabled('@withdrawTeam') : $clubFooter->assertButtonEnabled('@withdrawTeam');
                        ( ($user->cannot('update-teams'))  or ($league->state_count['registered'] == $league->size) ) ? $clubFooter->assertButtonDisabled('@injectTeam') : $clubFooter->assertButtonEnabled('@injectTeam');
                    });

                    $clubsCard->waitFor('@rowClub1');
                    $clubsCard->with('@rowClub1', function ($clubRow) use ($user, $league) {
                        ( ($user->cannot('update-leagues')) or ($league->state->in([ LeagueState::Live(), LeagueState::Setup()])) ) ? $clubRow->assertButtonDisabled('#deassignClub') : $clubRow->assertButtonEnabled('#deassignClub');
                    });
                    $clubsCard->with('@rowClub4', function ($clubRow) use ($user, $league) {
                        ( ($user->cannot('update-leagues'))  or ( $league->state->in([ LeagueState::Live(), LeagueState::Setup()])) ) ? $clubRow->assertButtonDisabled('#assignClub') : $clubRow->assertButtonEnabled('#assignClub');
                    });

                });
                $browser->with('#membersCard', function ($memberCard) use ($user, $member) {
                    $memberCard->click('.btn-tool')->waitFor('.btn-tool');
                    ($user->can('create-members')) ? $memberCard->assertButtonEnabled('#deleteMember') :  $memberCard->assertButtonDisabled('#deleteMember');
                    ($user->can('update-members')) ? $memberCard->assertSeeLink($member->name) : $memberCard->assertDontSeeLink($member->name);
                    ($user->can('update-members')) ? $memberCard->assertSeeLink(__('role.send.invite')) : $memberCard->assertDontSeeLink(__('role.send.invite'));
                    ($user->can('update-members')) ? $memberCard->assertButtonEnabled('#addMembership') : $memberCard->assertButtonDisabled('#addMembership');
                    ($user->can('update-members')) ? $memberCard->assertButtonEnabled('#modMembership') : $memberCard->assertButtonDisabled('#modMembership');
                });
                $browser->with('#gamesCard', function ($gameCard) use ($user, $league) {
                    $gameCard->click('.btn-tool')->waitFor('.btn-tool');
                    ( ($user->can('update-games')) and (  $league->state->is(LeagueState::Referees())) ) ? $gameCard->assertPresent('deleteNoshowGames') : $gameCard->assertNotPresent('deleteNoshowGames') ;
                    ($user->can('view-games')) ? $gameCard->assertSeeLink(__('league.action.game.list')) : $gameCard->assertDontSeeLink(__('league.action.game.list'));
                    ($user->can('view-games')) ? $gameCard->assertSeeLink(' iCAL') : $gameCard->assertDontSeeLink(' iCAL');
                });
            } else {
                $browser->assertSee('403');
            }
        });

    }

}