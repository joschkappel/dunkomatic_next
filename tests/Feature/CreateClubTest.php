<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestUsers;
use App\Club;
use App\Region;
use Illuminate\Support\Facades\Log;

class CreateClubTest extends TestCase
{
    protected $testUser;

    public function __construct() {
        $this->testUser = new TestUsers();
        parent::__construct();
    }

    use RefreshDatabase;

    /** @test  **/
    public function user_can_create_new_club()
    {

      $region_user = $this->testUser->getRegionUser();
      $region = Region::factory()->create();

      $response = $this->actingAs($region_user)
                        ->post('club', [
                          'shortname' => 'TEST',
                          'name' => 'Test club',
                          'region' => 'HBV',
                          'club_no' => '9999',
                          'url' => 'http://example.com',
                      ]);

      $club =  Club::where("club_no","=","9999")->first();
      Log::debug(print_r($club,true));
      $this->assertDatabaseHas('clubs', ['club_no' => '9999']);

      $response
          ->assertStatus(302)
          ->assertHeader('Location', url('/de/club'));
    }

    /** @test  **/
    public function club_is_not_created_if_validation_fails()
    {
      $region_user = $this->testUser->getRegionUser();

      $response = $this->actingAs($region_user)
                        ->post('club', [
                          'shortname' => 'test',
                          'name' => 'Test club',
                          'region' => 'XXX',
                          'club_no' => '',
                          'url' => 'xample.com',
                      ]);

      $response->assertSessionHasErrors([ 'shortname', 'url', 'club_no']);
      $this->assertDatabaseMissing('clubs', ['shortname' => 'test']);

    }

    /** @test  **/
    public function club_is_not_created_with_invalid_url()
    {
      $this->withoutExceptionHandling();

      $cases = ['//invalid-url.com', '/invalid-url', 'foo.com'];
      $region_user = $this->testUser->getRegionUser();

      foreach ($cases as $case) {
          try {
            $response = $this->actingAs($region_user)
                              ->post('club', [
                                'shortname' => 'test',
                                'name' => 'Test club',
                                'region' => 'XXX',
                                'club_no' => '',
                                'url' => $case,
                            ]);
          } catch (ValidationException $e) {
              $this->assertEquals(
                  'Das url-Format ist inkorrekt.',
                  $e->validator->errors()->first('url')
              );
              continue;
          }

          $this->fail("The URL $case passed validation when it should have failed.");
      }
      $this->assertDatabaseMissing('clubs', [
          'shortname' => 'test'
      ]);
    }

    /** @test  **/
    public function club_is_not_created_with_invalid_shortname() {}

    /** @test  **/
    public function user_can_delete_club()
    {

      $region_user = $this->testUser->getRegionUser();
      $region = Region::factory()->create();
      $response = $this->actingAs($region_user)
                        ->post('club', [
                          'shortname' => 'TEST',
                          'name' => 'Test club',
                          'region' => 'HBV',
                          'club_no' => '9999',
                          'url' => 'http://example.com',
                      ]);

      // get club id
      $club =  Club::where("club_no","=","9999")->first();

      $this->assertDatabaseHas('clubs', ['id' => $club->id]);

      $response = $this->actingAs($region_user)
                        ->delete('club/'.$club->id);

      $this->assertDatabaseMissing('clubs', ['id' => $club->id]);

      $response
          ->assertStatus(200);
    }
}
