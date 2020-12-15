<?php

namespace Tests\Unit;

use App\Models\Region;
use App\Enums\JobFrequencyType;
use App\Enums\ReportFileType;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

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
      $response = $this->authenticated()
                        ->get(route('region.set', ['region'=>$this->region]));

      $response->assertRedirect(route('home',['language'=>'de']))
               ->assertSessionHas('cur_region.code',$this->region->code);
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
    //  $this->withoutExceptionHandling();
      $response = $this->authenticated()
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
                       ->put(route('region.update',['region'=>$this->region]),[
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
                       ->put(route('region.update',['region'=>$this->region]),[
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
           ->assertHeader('Location', route('home',['language'=>'de']));
       $this->assertDatabaseHas('regions', ['name' => 'HBVDAupdated']);

    }



}
