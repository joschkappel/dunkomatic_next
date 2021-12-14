<?php

namespace Tests\Unit;

use App\Enums\JobFrequencyType;
use App\Enums\ReportFileType;
use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Notification;
use App\Enums\Role;
use App\Models\Club;
use App\Models\Member;
use App\Models\User;
use App\Models\Region;

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

      $response = $this->authenticated()
                        ->followingRedirects()
                        ->get(route('club.index', ['language'=>'de','region'=> $r2 ]));

      $response->assertSessionHas('cur_region.code',$r2->code);

      $response = $this->get(route('club.index', ['language'=>'de','region'=> $r1,'new_region'=>$r1]));

      $response->assertSessionHas('cur_region.code',$r1->code);


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
               ->assertJson([['id'=>$this->region->id,'text'=>$this->region->name]]);
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
      $sadmin = User::where('name','admin')->first();
    //  $this->withoutExceptionHandling();
      $response = $this->authenticated($sadmin)
                       ->get(route('region.edit',['language'=>'de', 'region'=>$this->region]));

      $response->assertStatus(200);
      $response->assertViewIs('region.region_edit')
               ->assertViewHas('region',$this->region);
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
                       ->put(route('region.update_details',['region'=>$this->region]),[
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
                       ->put(route('region.update_details',['region'=>$this->region]),[
                         'name' => 'HBVDAupdated',
                         'game_slot' => 150,
                         'job_noleads' => JobFrequencyType::getRandomValue(),
                         'job_game_notime' => JobFrequencyType::getRandomValue(),
                         'job_game_overlaps' => JobFrequencyType::getRandomValue(),
                         'job_email_valid' => JobFrequencyType::getRandomValue(),
                         'job_league_reports' => JobFrequencyType::getRandomValue(),
                         'job_club_reports' => JobFrequencyType::getRandomValue(),
                         'fmt_club_reports' => [ReportFileType::getRandomValue()],
                         'fmt_league_reports' =>[ReportFileType::getRandomValue(),ReportFileType::getRandomValue()],
                       ]);

       $response
           ->assertStatus(302)
           ->assertHeader('Location', route('region.dashboard',['language'=>'de','region'=>$this->region]));
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

      $sadmin = User::where('name','admin')->first();
      $response = $this->authenticated($sadmin)
                        ->get(route('region.index',['language'=>'de']));

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
        // enable and check that notificatons are sent
        Club::factory()->hasAttached( Member::factory()->count(1),['role_id'=>Role::ClubLead()])->create(['name'=>'testclub', 'region_id'=>$this->region->id]);
        $club = Club::where('name','testclub')->first();

        Notification::fake();
        Notification::assertNothingSent();

        $response = $this->authenticated()
                        ->put(route('region.update_details',['region'=>$this->region]),[
                                    'name' => 'HBVDAupdated',
                                    'game_slot' => 150,
                                    'job_noleads' => JobFrequencyType::getRandomValue(),
                                    'job_game_notime' => JobFrequencyType::getRandomValue(),
                                    'job_game_overlaps' => JobFrequencyType::getRandomValue(),
                                    'job_email_valid' => JobFrequencyType::getRandomValue(),
                                    'job_league_reports' => JobFrequencyType::getRandomValue(),
                                    'job_club_reports' => JobFrequencyType::getRandomValue(),
                                    'fmt_club_reports' => [ReportFileType::getRandomValue()],
                                    'fmt_league_reports' =>[ReportFileType::getRandomValue(),ReportFileType::getRandomValue()],
                                    'pickchar_enabled' => 'on'
                                    ]);

        // $response->dumpHeaders();
        $response->assertStatus(302)
                ->assertHeader('Location', route('region.dashboard',['language'=>'de','region'=>$this->region]));


        $club = $this->region->clubs()->first();
        $user = $club->members()->wherePivot('role_id', Role::ClubLead)->first();

        Notification::assertNothingSent();

        // disbale and check that notifications are sent
        $response = $this->authenticated()
                        ->put(route('region.update_details',['region'=>$this->region]),[
                                    'name' => 'HBVDAupdated',
                                    'game_slot' => 150,
                                    'job_noleads' => JobFrequencyType::getRandomValue(),
                                    'job_game_notime' => JobFrequencyType::getRandomValue(),
                                    'job_game_overlaps' => JobFrequencyType::getRandomValue(),
                                    'job_email_valid' => JobFrequencyType::getRandomValue(),
                                    'job_league_reports' => JobFrequencyType::getRandomValue(),
                                    'job_club_reports' => JobFrequencyType::getRandomValue(),
                                    'fmt_club_reports' => [ReportFileType::getRandomValue()],
                                    'fmt_league_reports' =>[ReportFileType::getRandomValue(),ReportFileType::getRandomValue()],
                                    'pickchar_enabled' => 'on'
                                    ]);
        Notification::assertNothingSent();


        // clean up club and member
        $user->delete();
        $club->delete();

    }

}
