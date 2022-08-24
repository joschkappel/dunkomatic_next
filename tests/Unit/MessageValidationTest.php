<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

class MessageValidationTest extends TestCase
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
    public function message_form_validation($formInput, $formInputValue): void
    {

      $response = $this->authenticated()
           ->post(route('message.store',['region'=> $this->region, 'user'=>$this->region_user ]), [$formInput => $formInputValue]);

      $response->assertSessionHasErrors($formInput);
    }

    public function memberForm(): array
    {
            return [
                'greeting missing' => ['greeting', ''],
                'title missing' => ['title', ''],
                'body missing' => ['body', ''],
                'salutation missing' => ['salutation', ''],
                'send at missing' => ['send_at', ''],
                'send at too old' => ['send_at', now()->subDays(2)]
            ];
    }
}
