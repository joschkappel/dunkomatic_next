<?php

use App\Models\League;
use App\Models\Game;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;


it('has region.upload.game page', function () {
    $league = League::factory(['shortname' => 'LEAG'])->frozen(4, 2)->create();
    $response = $this->authenticated()
        ->get(route('region.upload.game', ['language' => 'de', 'region' => $league->region]));

    $response->assertStatus(200)
        ->assertViewIs('game.game_file_upload')
        ->assertViewHas('context', 'referee');
});

it('can import region referees ok', function (string $name) {
    $league = League::factory(['shortname' => 'LEAG'])->frozen(4, 4)->create();

    // align game id's so that file matches:
    Game::where('game_no', 1)->first()->update(['id' => 1]);
    Game::where('game_no', 4)->first()->update(['id' => 4]);
    Game::where('game_no', 6)->first()->update(['id' => 6]);

    $stub = __DIR__ . '/stubs/' . $name;
    Storage::disk('local')->makeDirectory('importtest');
    $filename = Storage::putFileAs('importtest', new File($stub), $name);
    $path = Storage::disk('local')->path($filename);
    $file = new UploadedFile($path, $name, 'text/csv', null, true);

    $response = $this->authenticated()
        ->postJson(route('region.import.refgame', ['language' => 'de', 'region' => $league->region]), ['gfile' => $file]);

    $response
        ->assertStatus(302)
        //->assertSessionHasErrorsIn('display', [3, 4])
        ->assertSessionDoesntHaveErrors('display')
        ->assertSessionDoesntHaveErrors('file')
        ->assertSessionHas('status', 'All data imported');
})->with(['REGION_Bezirksspielplan_ok.csv', 'REGION_Bezirksspielplan_ok.xlsx']);

it('cannot import region refrees (.csv)', function () {
    $league = League::factory(['shortname' => 'LEAG'])->frozen(4, 4)->create();
    // align game id's so that file matches:
    Game::where('game_no', 1)->first()->update(['id' => 1]);
    Game::where('game_no', 4)->first()->update(['id' => 4]);
    Game::where('game_no', 6)->first()->update(['id' => 6]);
    $name = 'REGION_Bezirksspielplan_notok.csv';

    $stub = __DIR__ . '/stubs/' . $name;
    Storage::disk('local')->makeDirectory('importtest');
    $filename = Storage::putFileAs('importtest', new File($stub), $name);
    $path = Storage::disk('local')->path($filename);
    $file = new UploadedFile($path, $name, 'text/csv', null, true);

    $response = $this->authenticated()
        ->postJson(route('region.import.refgame', ['language' => 'de', 'region' => $league->region]), ['gfile' => $file]);

    $response
        ->assertStatus(302)
        ->assertSessionHasErrorsIn('display', [2, 4])
        ->assertSessionDoesntHaveErrors('file');
});
it('cannot import region refrees (.xlsx)', function () {
    $league = League::factory(['shortname' => 'LEAG'])->frozen(4, 4)->create();
    // align game id's so that file matches:
    Game::where('game_no', 1)->first()->update(['id' => 1]);
    Game::where('game_no', 4)->first()->update(['id' => 4]);
    Game::where('game_no', 6)->first()->update(['id' => 6]);
    $name = 'REGION_Bezirksspielplan_notok.xlsx';

    $stub = __DIR__ . '/stubs/' . $name;
    Storage::disk('local')->makeDirectory('importtest');
    $filename = Storage::putFileAs('importtest', new File($stub), $name);
    $path = Storage::disk('local')->path($filename);
    $file = new UploadedFile($path, $name, 'text/csv', null, true);

    $response = $this->authenticated()
        ->postJson(route('region.import.refgame', ['language' => 'de', 'region' => $league->region]), ['gfile' => $file]);

    $response
        ->assertStatus(302)
        ->assertSessionHasErrorsIn('file')
        ->assertSessionDoesntHaveErrors('display');
    $this->assertFileExists(Storage::disk('local')->path('Validated_' . $name));
});
