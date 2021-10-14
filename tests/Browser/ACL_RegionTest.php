<?php

namespace Tests\Browser;

use App\Models\Region;
use App\Models\Member;
use App\Models\User;

use Bouncer;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;


class ACL_RegionTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected static $region;
    protected static $member;
    protected static $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);

        static::$region = Region::where('code','HBVDA')->first();
        static::$member = Member::factory()->create();
        static::$user = User::factory()->approved()->for(static::$region)->for(static::$member)->create();

    }

    use withFaker;

    /**
     * @test
     * @group region
     * @group acl
     * @group superadmin
     */
    public function superadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'superadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_regionlist($user);
        $this->access_regiondashboard($user);
    }

    /**
     * @test
     * @group region
     * @group acl
     * @group regionadmin
     */
    public function regionadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'regionadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_regionlist($user);
        $this->access_regiondashboard($user);

    }

   /**
     * @test
     * @group region
     * @group acl
     * @group clubadmin
     */
    public function clubadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'clubadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_regionlist($user);
        $this->access_regiondashboard($user);

    }

   /**
     * @test
     * @group region
     * @group acl
     * @group clubassist
     */
    public function clubassist_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'clubassist')->to($user);
        Bouncer::refreshFor($user);

        $this->access_regionlist($user);
        $this->access_regiondashboard($user);

    }
   /**
     * @test
     * @group region
     * @group acl
     * @group leagueadmin
     */
    public function leagueadmin_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'leagueadmin')->to($user);
        Bouncer::refreshFor($user);

        $this->access_regionlist($user);
        $this->access_regiondashboard($user);

    }
   /**
     * @test
     * @group region
     * @group acl
     * @group guest
     */
    public function guest_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'guest')->to($user);
        Bouncer::refreshFor($user);

        $this->access_regionlist($user);
        $this->access_regiondashboard($user);

    }
   /**
     * @test
     * @group region
     * @group acl
     * @group candidate
     */
    public function candidate_acls()
    {
        $user = static::$user;
        Bouncer::retract( $user->getRoles()  )->from($user);
        Bouncer::assign( 'candidate')->to($user);
        Bouncer::refreshFor($user);

        $this->access_regionlist($user);
        $this->access_regiondashboard($user);;

    }


    private function access_regionlist( $user )
    {
        $region = static::$region;

        $this->browse(function ($browser) use ($user, $region) {
            $browser->loginAs($user)->visitRoute('region.index',['language'=>'de']);

            if ( $user->can('view-regions') ) {
                $browser->assertRouteIs('region.index',['language'=>'de']);
                ($user->can('create-regions')) ? $browser->assertSee(__('region.action.create',$locale=['de'])) : $browser->assertDontSee(__('region.action.create',$locale=['de']));
                $browser->waitFor('.table');

                if ($user->canAny(['create-regions', 'update-regions'])) {
                    $browser->assertSeeLink($region->code);
                    $browser->clickLink($region->code)
                            ->assertRouteIs('region.dashboard', ['language'=>'de','region'=>$region->id]);
                } else {
                    $browser->assertDontSeeLink($region->code)
                            ->assertSee($region->code);
                }

            } else {
                $browser->assertSee('403');
            }
        });
    }

    private function access_regiondashboard( $user)
    {
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)->visitRoute('region.dashboard',['language'=>'de', 'region'=>static::$region]); // ->screenshot($user->getRoles()[0]);
            $member = static::$member;
            $region = static::$region;

            if  ( $user->canAny(['create-regions', 'update-regions']) ){
                ($user->can('update-regions')) ? $browser->assertSee(__('region.action.edit',$locale=['de'])) : $browser->assertDontSee(__('region.action.edit',$locale=['de']));
                ($user->can('create-regions')) ? $browser->assertSee(__('region.action.delete',$locale=['de'])) : $browser->assertDontSee(__('region.action.delete',$locale=['de']));
                ($user->can('create-members')) ? $browser->assertSee(__('region.member.action.create',$locale=['de'])) : $browser->assertDontSee(__('region.member.action.create',$locale=['de']));
            }

        });

    }

}
