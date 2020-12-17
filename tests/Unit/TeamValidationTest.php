<?php

namespace Tests\Unit;

use App\Models\Club;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

class TeamValidationTest extends TestCase
{
    use Authentication;

    private $club;

    /**
      * team validation
      *
      * @test
      * @dataProvider teamForm
      * @group team
      * @group validation
      *
      * @return void
      */
    public function team_form_validation($formInput, $formInputValue): void
    {
      if  (Club::where('name','testclub')->exists()){
        $this->club = Club::where('name','testclub')->first();
      } else {
        $this->club = Club::factory()->create(['name'=>'testclub']);
      }

      $response = $this->authenticated()
           ->post(route('club.team.store',['club'=>$this->club->id]), [$formInput => $formInputValue]);

      $response->assertSessionHasErrors($formInput);
    }

    public function teamForm(): array
    {
            return [
                'team_no missing' => ['team_no', ''],
                'team_no 10 digits' => ['team_no', '1234567890'],
                'team_no string' => ['team_no', 'teamno'],
                'training_day missing' => ['training_day',''],
                'training_day string' => ['training_day','day'],
                'training_day grt 5' => ['training_day',6],
                'preferred_game_day missing' => ['preferred_game_day',''],
                'preferred_game_day string' => ['preferred_game_day','day'],
                'preferred_game_day grt 7' => ['preferred_game_day',10],
                'training_time missing' => ['training_time',''],
                'training_time no time' => ['training_time','day'],
                'training_time no H:s' => ['training_time','33:100'],
                'preferred_game_time missing' => ['preferred_game_time',''],
                'preferred_game_time no time' => ['preferred_game_time','day'],
                'preferred_game_time no H:s' => ['preferred_game_time','33:100'],
                'coach_name missing' => ['coach_name',''],
                'coach_email missing' => ['coach_email',''],
                'coach_email no email' => ['coach_email','testemail'],
                'coach_phone1 missing' => ['coach_phone1',''],
                'shirt_color missing' => ['shirt_color',''],
            ];
    }
}
