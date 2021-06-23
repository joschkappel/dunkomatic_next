<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

class MemberValidationTest extends TestCase
{
    use Authentication;

    /**
      * member validation
      *
      * @test
      * @dataProvider memberForm
      * @group member
      * @group validation
      *
      * @return void
      */
    public function member_form_validation($formInput, $formInputValue): void
    {

      $response = $this->authenticated()
           ->post(route('member.store'), [$formInput => $formInputValue]);

      $response->assertSessionHasErrors($formInput);
    }

    public function memberForm(): array
    {
            return [
                'firstname missing' => ['firstname', ''],
                'lastname missing' => ['lastname', ''],
                'zipcode missing' => ['zipcode', ''],
                'city missing' => ['city', ''],
                'street missing' => ['street', ''],
                'mobile missing' => ['mobile', ''],
                'phone missing' => ['phone', ''],
                'email1 missing' => ['email1', ''],
                'email1 no email' => ['email1', 'myemail'],
                'email2 no email' => ['email2', 'myemail'],
            ];
    }
}
