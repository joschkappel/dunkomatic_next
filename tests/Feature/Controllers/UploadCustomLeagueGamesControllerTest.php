<?php

use App\Models\League;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;


it('has game.upload page', function () {
    $league = League::factory(['shortname' => 'LEAG'])->frozen(4, 2)->create();
    $response = $this->authenticated()
        ->get(route('game.upload', ['language' => 'de', 'region' => $league->region]));

    $response->assertStatus(200)
        ->assertViewIs('game.game_file_upload')
        ->assertViewHas('context', 'customgames');
});

it('can import custom league games ok', function (string $name) {
    $league = League::factory(['shortname' => 'LEAG'])->custom()->frozen(4, 4)->create();

    $stub = __DIR__ . '/stubs/' . $name;
    Storage::disk('local')->makeDirectory('importtest');
    $filename = Storage::putFileAs('importtest', new File($stub), $name);
    $path = Storage::disk('local')->path($filename);
    $file = new UploadedFile($path, $name, 'text/csv', null, true);

    $response = $this->authenticated()
        ->postJson(route('region.import.customgame', ['language' => 'de', 'region' => $league->region]), ['gfile' => $file]);

    $response
        ->assertStatus(302)
        //->assertSessionHasErrorsIn('display', [3, 4])
        ->assertSessionDoesntHaveErrors('display')
        ->assertSessionDoesntHaveErrors('file')
        ->assertSessionHas('status', 'All data imported');
})->with(['CUSTOMLEAGUE_Spiele_ok.csv', 'CUSTOMLEAGUE_Spiele_ok.xlsx']);

it('cannot import custom league games (.csv)', function () {
    $league = League::factory(['shortname' => 'LEAG'])->custom()->frozen(4, 4)->create();
    $name = 'CUSTOMLEAGUE_Spiele_notok.csv';

    $stub = __DIR__ . '/stubs/' . $name;
    Storage::disk('local')->makeDirectory('importtest');
    $filename = Storage::putFileAs('importtest', new File($stub), $name);
    $path = Storage::disk('local')->path($filename);
    $file = new UploadedFile($path, $name, 'text/csv', null, true);

    $response = $this->authenticated()
        ->postJson(route('region.import.customgame', ['language' => 'de', 'region' => $league->region]), ['gfile' => $file]);

    $response
        ->assertStatus(302)
        ->assertSessionHasErrorsIn('display', [2, 4])
        ->assertSessionDoesntHaveErrors('file');
});
it('cannot import custom league homegames (.xlsx)', function () {
    $league = League::factory(['shortname' => 'LEAG'])->custom()->frozen(4, 4)->create();
    $name = 'CUSTOMLEAGUE_Spiele_notok.xlsx';

    $stub = __DIR__ . '/stubs/' . $name;
    Storage::disk('local')->makeDirectory('importtest');
    $filename = Storage::putFileAs('importtest', new File($stub), $name);
    $path = Storage::disk('local')->path($filename);
    $file = new UploadedFile($path, $name, 'text/csv', null, true);

    $response = $this->authenticated()
        ->postJson(route('region.import.customgame', ['language' => 'de', 'region' => $league->region]), ['gfile' => $file]);

    $response
        ->assertStatus(302)
        ->assertSessionHasErrorsIn('file')
        ->assertSessionDoesntHaveErrors('display');
    $this->assertFileExists(Storage::disk('local')->path('Validated_' . $name));
});
