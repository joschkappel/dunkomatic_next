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

class ClubFormTest extends TestCase
{
    protected $testUser;

    use RefreshDatabase;

    /**
     * @dataProvider clubForm
     * @group club
     */
    public function test_club_form_validation($formInput, $formInputValue)
    {
         $this->testUser = new TestUsers();
        $region_user = $this->testUser->getRegionUser();

        $this->post('club', [$formInput => $formInputValue])
            ->assertSessionHasErrors($formInput);
    }

    public function clubForm()
    {
            return [
                ['name', ''],
                ['shortname', 'ismu'],
                ['region', 'XXX'],
                ['url', 'lorem-ipsum'],
            ];
    }
}
