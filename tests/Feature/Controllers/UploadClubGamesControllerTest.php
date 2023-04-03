<?php

use App\Models\League;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;


it('has club.upload.homegames page', function () {
    $league = League::factory(['shortname' => 'LEAG'])->frozen(4, 2)->create();
    $club = $league->clubs->first();
    $response = $this->authenticated()
        ->get(route('club.upload.homegame', ['language' => 'de', 'club' => $club->id]));

    $response->assertStatus(200)
        ->assertViewIs('game.game_file_upload')
        ->assertViewHas('context', 'club');
});

it('can import club homegames ok', function (string $name) {
    $league = League::factory(['shortname' => 'LEAG'])->frozen(4, 4)->create();
    $club = $league->clubs->first();

    $stub = __DIR__ . '/stubs/' . $name;
    Storage::disk('local')->makeDirectory('importtest');
    $filename = Storage::putFileAs('importtest', new File($stub), $name);
    $path = Storage::disk('local')->path($filename);
    $file = new UploadedFile($path, $name, 'text/csv', null, true);

    $response = $this->authenticated()
        ->postJson(route('club.import.homegame', ['language' => 'de', 'club' => $club]), ['gfile' => $file]);

    $response
        ->assertStatus(302)
        //->assertSessionHasErrorsIn('display', [3, 4])
        ->assertSessionDoesntHaveErrors('display')
        ->assertSessionDoesntHaveErrors('file')
        ->assertSessionHas('status', 'All data imported');
})->with(['CLUB_Heimspiele_ok.csv', 'CLUB_Heimspiele_ok.xlsx']);

it('cannot import club homegames (.csv)', function () {
    $league = League::factory(['shortname' => 'LEAG'])->frozen(4, 4)->create();
    $club = $league->clubs->first();
    $name = 'CLUB_Heimspiele_notok.csv';

    $stub = __DIR__ . '/stubs/' . $name;
    Storage::disk('local')->makeDirectory('importtest');
    $filename = Storage::putFileAs('importtest', new File($stub), $name);
    $path = Storage::disk('local')->path($filename);
    $file = new UploadedFile($path, $name, 'text/csv', null, true);

    $response = $this->authenticated()
        ->postJson(route('club.import.homegame', ['language' => 'de', 'club' => $club]), ['gfile' => $file]);

    $response
        ->assertStatus(302)
        ->assertSessionHasErrorsIn('display', [2, 4])
        ->assertSessionDoesntHaveErrors('file');
});
it('cannot import club homegames (.xlsx)', function () {
    $league = League::factory(['shortname' => 'LEAG'])->frozen(4, 4)->create();
    $club = $league->clubs->first();
    $name = 'CLUB_Heimspiele_notok.xlsx';

    $stub = __DIR__ . '/stubs/' . $name;
    Storage::disk('local')->makeDirectory('importtest');
    $filename = Storage::putFileAs('importtest', new File($stub), $name);
    $path = Storage::disk('local')->path($filename);
    $file = new UploadedFile($path, $name, 'text/csv', null, true);

    $response = $this->authenticated()
        ->postJson(route('club.import.homegame', ['language' => 'de', 'club' => $club]), ['gfile' => $file]);

    $response
        ->assertStatus(302)
        ->assertSessionHasErrorsIn('file')
        ->assertSessionDoesntHaveErrors('display');
    $this->assertFileExists(Storage::disk('local')->path('Validated_' . $name));
});
