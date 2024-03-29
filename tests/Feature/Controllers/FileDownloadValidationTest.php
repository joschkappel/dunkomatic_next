<?php

namespace Tests\Feature\Controllers;

use App\Models\Club;
use Tests\Support\Authentication;
use Tests\TestCase;

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
             ->get(route('file.get', $formInput));

        //$response->dumpSession();
        $response->assertSessionHasErrors($formError);
    }

    public function fileForm(): array
    {
        return [
            'type missing' => ['type', ['type' => '']],
            'type wrong' => ['type', ['type' => 'type']],
            'file missing' => ['file', ['file' => '']],
            'file wrong' => ['type', ['file' => 123]],
            'club  missing' => ['club', ['type' => Club::class, 'file' => 'test.csv']],
            'league missing' => ['league', ['type' => Club::class, 'file' => 'test.csv']],
            'club not existing' => ['club', ['type' => Club::class, 'file' => 'test.csv', 'club' => 999]],
            'league not existing' => ['league', ['type' => Club::class, 'file' => 'test.csv', 'league' => 999]],
        ];
    }
}
