<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestUsers;
use App\Models\Club;
use App\Models\Region;
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

    /**
     * @test
     */
    public function club_is_not_created_with_invalid_shortname()
    {
      $this->withoutExceptionHandling();
      $region_user = $this->testUser->getRegionUser();

      try {
        $response = $this->actingAs($region_user)
                          ->post('club', [
                            'shortname' => 'ts',
                            'name' => 'Test club',
                            'region' => 'XXX',
                            'club_no' => '9999',
                            'url' => 'http://google.com'
                        ]);
      } catch (ValidationException $e) {
          $this->assertEquals(
              'Code muss mindestens 4 Zeichen lang sein.',
              $e->validator->errors()->first('shortname')
          );
      }

      try {
        $response = $this->actingAs($region_user)
                          ->post('club', [
                            'shortname' => 'toolong',
                            'name' => 'Test club',
                            'region' => 'XXX',
                            'club_no' => '9999',
                            'url' => 'http://google.com'
                        ]);
      } catch (ValidationException $e) {
          $this->assertEquals(
              'Code darf nicht länger als 4 Zeichen sein.',
              $e->validator->errors()->first('shortname')
          );
      }

      $this->assertDatabaseMissing('clubs', [
          'club_no' => '9999'
      ]);
    }

    /**
     * @test
     */
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
