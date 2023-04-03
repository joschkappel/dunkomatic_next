<?php

use App\Models\League;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;


it('has league.upload.game page', function () {
    $league = League::factory(['shortname' => 'LEAG'])->frozen(4, 2)->create();
    $response = $this->authenticated()
        ->get(route('league.upload.game', ['language' => 'de', 'league' => $league]));

    $response->assertStatus(200)
        ->assertViewIs('game.game_file_upload')
        ->assertViewHas('context', 'league');
});

it('can import league games ok', function (string $name) {
    $league = League::factory(['shortname' => 'LEAG'])->frozen(4, 4)->create();
    $club = $league->clubs->first();

    $stub = __DIR__ . '/stubs/' . $name;
    Storage::disk('local')->makeDirectory('importtest');
    $filename = Storage::putFileAs('importtest', new File($stub), $name);
    $path = Storage::disk('local')->path($filename);
    $file = new UploadedFile($path, $name, 'text/csv', null, true);

    $response = $this->authenticated()
        ->postJson(route('league.import.game', ['language' => 'de', 'league' => $league]), ['gfile' => $file]);

    $response
        ->assertStatus(302)
        //->assertSessionHasErrorsIn('display', [3, 4])
        ->assertSessionDoesntHaveErrors('display')
        ->assertSessionDoesntHaveErrors('file')
        ->assertSessionHas('status', 'All data imported');
})->with(['LEAGUE_Spiele_ok.csv', 'LEAGUE_Spiele_ok.xlsx']);

it('cannot import league games (.csv)', function () {
    $league = League::factory(['shortname' => 'LEAG'])->frozen(4, 4)->create();
    $name = 'LEAGUE_Spiele_notok.csv';

    $stub = __DIR__ . '/stubs/' . $name;
    Storage::disk('local')->makeDirectory('importtest');
    $filename = Storage::putFileAs('importtest', new File($stub), $name);
    $path = Storage::disk('local')->path($filename);
    $file = new UploadedFile($path, $name, 'text/csv', null, true);

    $response = $this->authenticated()
        ->postJson(route('league.import.game', ['language' => 'de', 'league' => $league]), ['gfile' => $file]);

    $response
        ->assertStatus(302)
        ->assertSessionHasErrorsIn('display', [2, 4])
        ->assertSessionDoesntHaveErrors('file');
});
it('cannot import league games (.xlsx)', function () {
    $league = League::factory(['shortname' => 'LEAG'])->frozen(4, 4)->create();
    $club = $league->clubs->first();
    $name = 'LEAGUE_Spiele_notok.xlsx';

    $stub = __DIR__ . '/stubs/' . $name;
    Storage::disk('local')->makeDirectory('importtest');
    $filename = Storage::putFileAs('importtest', new File($stub), $name);
    $path = Storage::disk('local')->path($filename);
    $file = new UploadedFile($path, $name, 'text/csv', null, true);

    $response = $this->authenticated()
        ->postJson(route('league.import.game', ['language' => 'de', 'league' => $league]), ['gfile' => $file]);

    $response
        ->assertStatus(302)
        ->assertSessionHasErrorsIn('file')
        ->assertSessionDoesntHaveErrors('display');
    $this->assertFileExists(Storage::disk('local')->path('Validated_' . $name));
});
