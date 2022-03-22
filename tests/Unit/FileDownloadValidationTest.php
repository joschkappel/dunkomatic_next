<?php

namespace Tests\Unit;

use App\Models\Club;
use Tests\TestCase;
use Tests\Support\Authentication;

class FileDownloadValidationTest extends TestCase
{
    use Authentication;

    /**
      * file download validation
      *
      * @test
      * @dataProvider fileForm
      * @group game
      * @group validation
      *
      * @return void
      */
    public function filedownload_validation($formError, $formInput): void
    {

      $response = $this->authenticated()
           ->get(route('file.get', $formInput ));

      //$response->dumpSession();
      $response->assertSessionHasErrors($formError);
    }

    public function fileForm(): array
    {
        return [
            'type missing' => ['type', ['type' => '']],
            'type wrong' => ['type', ['type'=>'type']],
            'file missing' => ['file', ['file'=>'']],
            'file wrong' => ['type', ['file' => 123 ] ],
            'club  missing' => ['club', array('type'=>Club::class, 'file'=>'test.csv') ],
            'league missing' => ['league', array('type'=>Club::class, 'file'=>'test.csv') ],
            'club not existing' => ['club', array('type'=>Club::class, 'file'=>'test.csv', 'club'=> 999 ) ],
            'league not existing' => ['league', array('type'=>Club::class, 'file'=>'test.csv', 'league'=> 999 ) ]
        ];
    }
}
