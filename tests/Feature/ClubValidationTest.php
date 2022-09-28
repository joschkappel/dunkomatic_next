<?php

namespace Tests\Feature;

use Tests\Support\Authentication;
use Tests\TestCase;

class ClubValidationTest extends TestCase
{
    use Authentication;

    /**
     * club validation
     *
     * @test
     * @dataProvider clubForm
     * @group club
     * @group validation
     *
     * @return void
     */
    public function club_form_validation($formInput, $formInputValue): void
    {
        $response = $this->authenticated()
             ->post(route('club.store', ['region' => $this->region]), [$formInput => $formInputValue]);

        $response->assertSessionHasErrors($formInput);
    }

    public function clubForm(): array
    {
        return [
            'name missing' => ['name', ''],
            'shortname small chars' => ['shortname', 'ismu'],
            'shortname 5 chars' => ['shortname', 'AAAAA'],
            'shortname 3 chars' => ['shortname', 'AAA'],
            // 'url missing' => ['url', ''],
            'url wrong' => ['url', 'lorem-ipsum'],
            'club_no missing' => ['club_no', ''],
            'club_no 8 digits' => ['club_no', '12345678'],
        ];
    }
}
