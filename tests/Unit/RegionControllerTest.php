<?php

namespace Tests\Unit;

use App\Enums\JobFrequencyType;
use App\Enums\ReportFileType;
use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Region;

use Illuminate\Testing\Fluent\AssertableJson;

class RegionControllerTest extends TestCase
{
    use Authentication;

    /**
     * set_region.
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function set_region()
    {

        $r1 = Region::find(1);
        $r2 = Region::find(2);

        $region_init = $this->region_user->regions()->first();

        $response = $this->authenticated()
            ->followingRedirects()
            ->get(route('club.index', ['language' => 'de', 'region' => $r2]));

        $response->assertSessionHas('cur_region.code', $r2->code);

        $response = $this->get(route('club.index', ['language' => 'de', 'region' => $r1, 'new_region' => $r1]));

        $response->assertSessionHas('cur_region.code', $r1->code);
    }
    /**
     * dashboard
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function dashboard()
    {
        $response = $this->authenticated()
            ->get(route('region.dashboard', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertViewIs('region.region_dashboard')
            ->assertViewHas('region', $this->region)
            ->assertViewHas('members')
            ->assertViewHas('member_count')
            ->assertViewHas('games_count')
            ->assertViewHas('games_noref_count');
    }

    /**
     * briefing
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function briefing()
    {
        $response = $this->authenticated()
            ->get(route('region.briefing', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertViewIs('region.region_briefing')
            ->assertViewHas('region', $this->region)
            ->assertViewHas('memberships')
            ->assertViewHas('clubs')
            ->assertViewHas('leagues');
    }

    /**
     * create
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function create()
    {
        $response = $this->authenticated()
            ->get(route('region.create', ['language' => 'de']));

        $response->assertStatus(200)
            ->assertViewIs('region.region_new');
    }

    /**
     * store NOT OK
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function store_notok()
    {
        $response = $this->authenticated()
            ->post(route('region.store'), [
                'name' => 'testregion'
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['code']);

        $this->assertDatabaseMissing('regions', ['name' => 'testregion']);
    }
    /**
     * store OK
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function store_ok()
    {

        $response = $this->authenticated()
            ->post(route('region.store'), [
                'name' => 'testregion',
                'code' => 'TEST'
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertHeader('Location', route('region.index', ['language' => 'de']));

        $this->assertDatabaseHas('regions', ['name' => 'testregion']);
    }
    /**
     * store HQ NOT OK
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function store_hq_notok()
    {
        $response = $this->authenticated()
            ->post(route('region.store'), [
                'region_id' => 99,
                'name' => 'testregion2'
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['code', 'region_id']);

        $this->assertDatabaseMissing('regions', ['name' => 'testregion2']);
    }
    /**
     * store HQ OK
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function store_hq_ok()
    {

        $response = $this->authenticated()
            ->post(route('region.store'), [
                'name' => 'testregion2',
                'code' => 'TEST2',
                'region_id' => $this->region->parentRegion->id
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertHeader('Location', route('region.index', ['language' => 'de']));

        $this->assertDatabaseHas('regions', ['name' => 'testregion2']);
    }

    /**
     * datatable
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function datatable()
    {
        // test base level region
        $response = $this->authenticated()
            ->get(route('region.list.dt', ['language' => 'de']));

        $response->assertStatus(200)
            ->assertJsonFragment(['code' => '<a href="'.config('app.url').'/de/region/'.$this->region->id.'/dashboard">'.$this->region->code.'</a>']);
    }
    /**
     * admin_sb.
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function admin_sb()
    {
        $response = $this->get(route('region.admin.sb'));

        $response->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertJson([['id' => $this->region->id, 'text' => $this->region->name]]);
    }
    /**
     * hq_sb.
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function hq_sb()
    {
        $response = $this->authenticated()->get(route('region.hq.sb'));

        $response->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertJson([['id' => $this->region->parentRegion->id, 'text' => $this->region->parentRegion->name]]);
    }

    /**
     * edit.
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function edit()
    {
        $sadmin = User::where('name', 'admin')->first();
        //  $this->withoutExceptionHandling();
        $response = $this->authenticated($sadmin)
            ->get(route('region.edit', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200);
        $response->assertViewIs('region.region_edit')
            ->assertViewHas('region', $this->region);
    }
    /**
     * update NOT OK.
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function update_notok()
    {
        //$this->withoutExceptionHandling();
        $response = $this->authenticated()
            ->put(route('region.update_details', ['region' => $this->region]), [
                'name' => 'HBVDAupdated2',
                'game_slot' => 200,
            ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['game_slot']);
        $this->assertDatabaseMissing('regions', ['name' => 'HBVDAupdated2']);
    }

    /**
     * update OK.
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function update_ok()
    {
        //$this->withoutExceptionHandling();
        $response = $this->authenticated()
            ->put(route('region.update_details', ['region' => $this->region]), [
                'name' => 'HBVDAupdated',
                'game_slot' => 150,
                'job_noleads' => JobFrequencyType::getRandomValue(),
                'job_game_notime' => JobFrequencyType::getRandomValue(),
                'job_game_overlaps' => JobFrequencyType::getRandomValue(),
                'job_email_valid' => JobFrequencyType::getRandomValue(),
                'job_league_reports' => JobFrequencyType::getRandomValue(),
                'job_club_reports' => JobFrequencyType::getRandomValue(),
                'fmt_club_reports' => [ReportFileType::getRandomValue()],
                'fmt_league_reports' => [ReportFileType::getRandomValue(), ReportFileType::getRandomValue()],
            ]);

        $response
            ->assertStatus(302)
            ->assertViewIs('region.region_edit');
        $this->assertDatabaseHas('regions', ['name' => 'HBVDAupdated']);
    }

    /**
     * index
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function index()
    {

        $sadmin = User::where('name', 'admin')->first();
        $response = $this->authenticated($sadmin)
            ->get(route('region.index', ['language' => 'de']));

        $response->assertStatus(200)
            ->assertViewIs('region.region_list');
    }

    /**
     * enable char picking notifications
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function charpick_enabling()
    {
        Notification::fake();
        Notification::assertNothingSent();

        $response = $this->authenticated()
            ->put(route('region.update_details', ['region' => $this->region]), [
                'name' => 'HBVDAupdated',
                'game_slot' => 150,
                'job_noleads' => JobFrequencyType::getRandomValue(),
                'job_game_notime' => JobFrequencyType::getRandomValue(),
                'job_game_overlaps' => JobFrequencyType::getRandomValue(),
                'job_email_valid' => JobFrequencyType::getRandomValue(),
                'job_league_reports' => JobFrequencyType::getRandomValue(),
                'job_club_reports' => JobFrequencyType::getRandomValue(),
                'fmt_club_reports' => [ReportFileType::getRandomValue()],
                'fmt_league_reports' => [ReportFileType::getRandomValue(), ReportFileType::getRandomValue()],
                'pickchar_enabled' => 'on'
            ]);

        // $response->dumpHeaders();
        $response->assertStatus(302)
            ->assertViewIs('region.region_edit');

        Notification::assertNothingSent();

        // disbale and check that notifications are sent
        $response = $this->authenticated()
            ->put(route('region.update_details', ['region' => $this->region]), [
                'name' => 'HBVDAupdated',
                'game_slot' => 150,
                'job_noleads' => JobFrequencyType::getRandomValue(),
                'job_game_notime' => JobFrequencyType::getRandomValue(),
                'job_game_overlaps' => JobFrequencyType::getRandomValue(),
                'job_email_valid' => JobFrequencyType::getRandomValue(),
                'job_league_reports' => JobFrequencyType::getRandomValue(),
                'job_club_reports' => JobFrequencyType::getRandomValue(),
                'fmt_club_reports' => [ReportFileType::getRandomValue()],
                'fmt_league_reports' => [ReportFileType::getRandomValue(), ReportFileType::getRandomValue()],
                'pickchar_enabled' => 'on'
            ]);
        Notification::assertNothingSent();

    }

    /**
     * league_state_chart
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function league_state_chart()
    {

        $response = $this->authenticated()
            ->get(route('region.league.state.chart', ['region' => $this->region]));

        $response->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll('labels', 'datasets')
            );
    }
    /**
     * league_socio_chart
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function league_socio_chart()
    {

        $response = $this->authenticated()
            ->get(route('region.league.socio.chart', ['region' => $this->region]));

        $response->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll('labels', 'datasets')
            );
    }
    /**
     * club_team_chart
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function club_team_chart()
    {

        $response = $this->authenticated()
            ->get(route('region.club.team.chart', ['region' => $this->region]));

        $response->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll('labels', 'datasets')
            );
    }
    /**
     * club_member_chart
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function club_member_chart()
    {

        $response = $this->authenticated()
            ->get(route('region.club.member.chart', ['region' => $this->region]));

        $response->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll('labels', 'datasets')
            );
    }
    /**
     * game_noreferee_chart
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function game_noreferee_chart()
    {

        $response = $this->authenticated()
            ->get(route('region.game.noreferee.chart', ['region' => $this->region]));

        $response->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll('labels', 'datasets')
            );
    }
    /**
     * region_club_chart
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function region_club_chart()
    {

        $response = $this->authenticated()
            ->get(route('region.region.club.chart', ['region' => $this->region->parentRegion]));

        $response->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll('labels', 'datasets')
            );
    }
    /**
     * region_league_chart
     *
     * @test
     * @group region
     * @group controller
     *
     * @return void
     */
    public function region_league_chart()
    {

        $response = $this->authenticated()
            ->get(route('region.region.league.chart', ['region' => $this->region->parentRegion]));

        $response->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll('labels', 'datasets')
            );
    }
    /**
     * destroy
     *
     * @test
     * @group region
     * @group destroy
     * @group controller
     *
     * @return void
     */
    public function destroy()
    {
        $region = Region::where('name', 'testregion')->first();
        $response = $this->authenticated()
            ->delete(route('region.destroy', ['region' => $region]));

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('regions', ['id' => $region->id]);

        $region = Region::where('name', 'testregion2')->first();
        $response = $this->authenticated()
            ->delete(route('region.destroy', ['region' => $region]));

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('regions', ['id' => $region->id]);
    }
}
