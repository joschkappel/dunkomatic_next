<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

class LeagueValidationTest extends TestCase
{
    use Authentication;

    /**
      * league validation
      *
      * @test
      * @dataProvider leagueForm
      * @group league
      * @group validation
      *
      * @return void
      */
    public function league_form_validation($formInput, $formInputValue): void
    {

      $response = $this->authenticated()
           ->post(route('league.store',['region'=>$this->region]), [$formInput => $formInputValue]);

      $response->assertSessionHasErrors($formInput);
    }

    public function leagueForm(): array
    {
            return [
                'name missing' => ['name', ''],
                'shortname 11 chars' => ['shortname', 'AAAABBBBCCCC'],
                'schedule missing' => ['schedule_id', ''],
                'schedule wrong' => ['schedule_id', '100'],
                'age type missing' => ['age_type', ''],
                'age type wrong' => ['age_type', '100'],
                'gender type missing' => ['gender_type', ''],
                'gender type wrong' => ['gender_type', '100'],
            ];
    }
}
