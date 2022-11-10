<?php

namespace Tests\Browser;

use App\Models\Club;
use App\Models\Region;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Silber\Bouncer\BouncerFacade as Bouncer;
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
                    ->visit(route('club.create', ['language' => 'de', 'region' => $r]))
                    ->typeSlowly('input[id=shortname]', 'VVVV')
                    ->typeSlowly('input[id=name]', $club_name)
                    ->typeSlowly('input[id=club_no]', $club_no)
                    ->typeSlowly('input[id=url]', fake()->url)
                    ->screenshot('new_club_1_1')
                    ->waitUntilEnabled('.btn-primary')
                    ->screenshot('new_club_1_2')
                    ->press('Senden')
                    ->screenshot('new_club_1');
        });

        $this->assertDatabaseHas('clubs', ['club_no' => $club_no]);

        $club = Club::where('club_no', $club_no)->first();

        $this->browse(function ($browser) use ($club, $club_name_new, $club_no_new) {
            $browser->visit(route('club.edit', ['language' => 'de', 'club' => $club]))
                    ->typeSlowly('input[id=name]', $club_name_new)
                    ->typeSlowly('input[id=club_no]', $club_no_new)
                    ->screenshot('new_club_2_1')
                    ->waitUntilEnabled('.btn-primary')
                    ->screenshot('new_club_2_2')
                    ->press('Senden')
                    ->screenshot('new_club_2');
        });

        $this->assertDatabaseHas('clubs', ['club_no' => $club_no_new]);
    }
}
