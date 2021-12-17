<?php

namespace Tests\Browser;

use App\Models\Region;
use App\Models\Member;
use App\Models\User;

use Silber\Bouncer\BouncerFacade as Bouncer;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;


class ACL_UserTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected static $region;
    protected static $member;
    protected static $user;
    protected static $user_approved;
    protected static $user_notapproved;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);

        static::$region = Region::where('code','HBVDA')->first();
        static::$member = Member::factory()->create();
        static::$user = User::factory()->approved()->for(static::$member)->create();
        Bouncer::allow(static::$user)->to('access',static::$region);

        static::$user_approved = User::where('name','approved')->first();
        static::$user_notapproved = User::where('name','notapproved')->first();
    }

    use withFaker;

    /**
     * @test
     * @group user
     * @group acl
     * @group superadmin
     */
    public function superadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'superadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_userlist($user);
        $this->access_userapprovelist($user);
    }

    /**
     * @test
     * @group user
     * @group acl
     * @group regionadmin
     */
    public function regionadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'regionadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_userlist($user);
        $this->access_userapprovelist($user);
    }

   /**
     * @test
     * @group user
     * @group acl
     * @group clubadmin
     */
    public function clubadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'clubadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_userlist($user);
        $this->access_userapprovelist($user);
    }

   /**
     * @test
     * @group user
     * @group acl
     * @group leagueadmin
     */
    public function leagueadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'leagueadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_userlist($user);
        $this->access_userapprovelist($user);
    }
   /**
     * @test
     * @group user
     * @group acl
     * @group guest
     */
    public function guest_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'guest')->to($user);
        Bouncer::refreshFor($user);

        $this->access_userlist($user);
        $this->access_userapprovelist($user);
    }

    /**
     * @test
     * @group user
     * @group acl
     * @group candidate
     */
    public function candidate_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'candidate')->to($user);
        Bouncer::refreshFor($user);

        $this->access_userlist($user);
        $this->access_userapprovelist($user);
    }


    private function access_userapprovelist( $user )
    {
        $u2a = static::$user_approved;
        $u2na = static::$user_notapproved;

        $this->browse(function ($browser) use ($user, $u2a, $u2na) {
            $browser->loginAs($user)->visitRoute('admin.user.index.new',['language'=>'de','region'=>static::$region]);

            if ( $user->can('update-users') ) {
                $browser->assertRouteIs('admin.user.index.new',['language'=>'de','region'=>static::$region])->waitFor('.table');

                $browser->with('.table', function ($sRow) use ($user, $u2a, $u2na) {
                    $sRow->waitForText($u2na->name);
                    $sRow->assertPresent('#btnApprove')->assertSee($u2na->name)->assertButtonEnabled('#btnApprove');

                    $sRow->press('#btnApprove')
                         ->assertRouteIs('admin.user.edit', ['language'=>'de','user'=>$u2na->id]);

                });

            } else {
                $browser->assertSee('403');
            }
        });
    }

    private function access_userlist( $user )
    {
        $u2a = static::$user_approved;
        $u2na = static::$user_notapproved;

        $this->browse(function ($browser) use ($user, $u2a, $u2na) {
            $browser->loginAs($user)->visitRoute('admin.user.index',['language'=>'de','region'=>static::$region]);

            if ( $user->can('view-users') ) {
                $browser->assertRouteIs('admin.user.index',['language'=>'de','region'=>static::$region])->waitFor('.table');

                if ($user->canAny('update-users')) {
                    $browser->with('.table', function ($sRow) use ($user, $u2a, $u2na) {
                        $sRow->waitForLink($u2a->name);
                        $sRow->waitForText($u2na->name);

                        $sRow->assertDontSeeLink($u2na->name)->assertSee($u2na->name);

                        $sRow->assertSeeLink($u2a->name);
                        $sRow->clickLink($u2a->name)
                                ->assertRouteIs('admin.user.edit', ['language'=>'de','user'=>$u2a->id]);
                    });
                    $browser->assertPresent('@frmClose')->press('@frmClose')->waitFor('.table');

                    $browser->with('.table', function ($sRow) use ($user, $u2a) {
                        $sRow->waitForLink($u2a->name);
                        ($user->can('update-users')) ? $sRow->assertPresent('#blockUser') : $sRow->assertNotPresent('#blockUser');
                        ($user->can('create-users')) ? $sRow->assertPresent('#deleteUser') : $sRow->assertNotPressent('#deleteUser');
                    });
                } else {
                    $browser->with('.table', function ($sRow) use ($user, $u2a, $u2na) {
                        $sRow->assertDontSeeLink($u2a->name)
                                ->assertSee($u2a->name)
                                ->assertSeeDontSeeLink($u2na->name)
                                ->assertSee($u2na->name);
                    });
                }

            } else {
                $browser->assertSee('403');
            }
        });
    }

}
