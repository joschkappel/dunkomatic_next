<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Models\Club;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use TestDatabaseSeeder;

class CreateClubTest extends TestCase
{

    use RefreshDatabase;

    /** @test  **/
    public function user_can_create_new_club()
    {

      $this->seed(TestDatabaseSeeder::class);
      $this->assertDatabaseHas('regions', ['code' => 'HBVDA']);
      $this->assertDatabaseHas('users', ['region' => 'HBVDA']);

      $region = Region::where('code','HBVDA')->first();
      $region_user = User::regionadmin($region->code)->first();

      $response = $this->actingAs($region_user)
                        ->post('club', [
                          'shortname' => 'TEST',
                          'name' => 'Test club',
                          'region' => 'HBVDA',
                          'club_no' => '9999',
                          'url' => 'http://example.com',
                      ]);

      $club =  Club::where("club_no","=","9999")->first();
      //Log::debug(print_r($club,true));
      $this->assertDatabaseHas('clubs', ['club_no' => '9999']);

      $response
          ->assertStatus(302)
          ->assertHeader('Location', url('/de/club'));
    }

    /** @test  **/
    public function club_is_not_created_if_validation_fails()
    {

      $this->seed(TestDatabaseSeeder::class);
      $this->assertDatabaseHas('regions', ['code' => 'HBVDA']);
      $this->assertDatabaseHas('users', ['region' => 'HBVDA']);

      $region = Region::where('code','HBVDA')->first();
      $region_user = User::regionadmin($region->code)->first();

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
      $this->seed(TestDatabaseSeeder::class);
      $this->assertDatabaseHas('regions', ['code' => 'HBVDA']);
      $this->assertDatabaseHas('users', ['region' => 'HBVDA']);

      $region = Region::where('code','HBVDA')->first();
      $region_user = User::regionadmin($region->code)->first();

      $this->withoutExceptionHandling();

      $cases = ['//invalid-url.com', '/invalid-url', 'foo.com'];

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
      $this->seed(TestDatabaseSeeder::class);
      $this->assertDatabaseHas('regions', ['code' => 'HBVDA']);
      $this->assertDatabaseHas('users', ['region' => 'HBVDA']);

      $region = Region::where('code','HBVDA')->first();
      $region_user = User::regionadmin($region->code)->first();

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

      $this->seed(TestDatabaseSeeder::class);
      $this->assertDatabaseHas('regions', ['code' => 'HBVDA']);
      $this->assertDatabaseHas('users', ['region' => 'HBVDA']);

      $region = Region::where('code','HBVDA')->first();
      $region_user = User::regionadmin($region->code)->first();

      $response = $this->actingAs($region_user)
                        ->post('club', [
                          'shortname' => 'TEST',
                          'name' => 'Test club',
                          'region' => 'HBVDA',
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
