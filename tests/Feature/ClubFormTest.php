<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Illuminate\Support\Facades\Log;
use TestDatabaseSeeder;
use App\Models\Region;
use App\Models\User;

class ClubFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider clubForm
     * @group club
     */
    public function test_club_form_validation($formInput, $formInputValue): void
    {

      $this->seed(TestDatabaseSeeder::class);
      $this->assertDatabaseHas('regions', ['code' => 'HBVDA']);
      $this->assertDatabaseHas('users', ['region' => 'HBVDA']);

      $region = Region::where('code','HBVDA')->first();
      $region_user = $region->regionadmin->first()->user()->first();
      $response = $this->actingAs($region_user)
           ->post('club', [$formInput => $formInputValue]);

      $response->assertSessionHasErrors($formInput);
    }

    public function clubForm(): array
    {
            return [
                'name' => ['name', ''],
                'shortname' => ['shortname', 'ismu'],
                'region' => ['region', 'XXX'],
                'url' => ['url', 'lorem-ipsum'],
            ];
    }
}
