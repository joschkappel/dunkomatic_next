<?php

namespace Tests\Browser;

use App\Models\Region;
use App\Models\Club;
use App\Models\Member;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Browser\Pages\Club\NewClub;
use Tests\Browser\Pages\Club\EditClub;
use TestDatabaseSeeder;

class ClubTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(TestDatabaseSeeder::class);
    }

    use withFaker;
    /**
     * @test
     * @group club
     * @group db
     */
    public function create_club()
    {
        $r = Region::where('code','HBVDA')->first();
        $u = $r->regionadmin->first()->user()->first();
        $club_no = '1234567';
        $club_no_new = '1122334';
        $club_name = 'VVV';
        $club_name_new = 'VVVXXX';

        $this->browse(function ($browser) use ($u, $club_no, $club_name) {
          $browser->loginAs($u)
                  ->visit(new NewClub)
                  ->new_club($club_name, $club_no, $this->faker->url);
          });

          $this->assertDatabaseHas('clubs', ['club_no' => $club_no]);;

          $club = Club::where('shortname','VVVV')->first();

          $this->browse(function ($browser) use ($club_name_new, $club_no_new, $club) {
            $browser->visit(new EditClub($club->id))
                    ->modify_clubno($club_name_new, $club_no_new);
          });

          $this->assertDatabaseHas('clubs', ['club_no' => $club_no_new]);


    }

}
