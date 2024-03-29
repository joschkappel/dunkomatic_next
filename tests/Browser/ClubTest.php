<?php

namespace Tests\Browser;

use App\Models\Club;
use App\Models\Region;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Tests\Browser\Pages\Club\EditClub;
use Tests\Browser\Pages\Club\NewClub;
use Tests\DuskTestCase;

class ClubTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
    }

    use withFaker;

    /**
     * @test
     * @group club
     * @group db
     */
    public function create_club()
    {
        $r = Region::where('code', 'HBVDA')->first();
        $u = $r->regionadmins->first()->user()->first();
        Bouncer::retract($u->getRoles())->from($u);
        Bouncer::assign('superadmin')->to($u);
        Bouncer::refreshFor($u);

        $club_no = '1234567';
        $club_no_new = '1122334';
        $club_name = 'VVV';
        $club_name_new = 'VVVXXX';

        $this->browse(function ($browser) use ($u, $club_no, $club_name, $r) {
            $browser->loginAs($u)
                    ->visit(new NewClub($r->id))
                    ->new_club($club_name, $club_no, $this->faker->url);
        });

        $this->assertDatabaseHas('clubs', ['club_no' => $club_no]);

        $club = Club::where('club_no', $club_no)->first();

        $this->browse(function ($browser) use ($club_name_new, $club_no_new, $club) {
            $browser->visit(new EditClub($club->id))
                    ->modify_clubno($club_name_new, $club_no_new);
        });

        $this->assertDatabaseHas('clubs', ['club_no' => $club_no_new]);
    }
}
