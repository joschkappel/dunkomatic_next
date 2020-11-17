<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Illuminate\Support\Facades\Log;

class ClubFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider clubForm
     * @group club
     */
    public function test_club_form_validation($formInput, $formInputValue): void
    {

      $this->followingRedirects()
           ->post('club', [$formInput => $formInputValue])
           //->assertSuccessful();
           ->assertSessionHasErrors($formInput);
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
