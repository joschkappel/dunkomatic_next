<?php

use App\Models\Region;
use App\Enums\Role;
use App\Models\Message;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('can create a message with one attachment', function () {
    // Arrange
    $region = Region::where('code', 'HBVDA')->first();
    $attach1 = UploadedFile::fake()->create('apdf.pdf', 70);

    // Act
    $response = $this->authenticated()
        ->post(route('message.store', ['region' => $region, 'user' => $region->users()->first()]), [
            'title' => 'testmessage1',
            'body' => 'this is a test',
            'greeting' => 'hello',
            'salutation' => 'all',
            'send_at' => now(),
            'delete_at' => now()->addDays(3),
            'to_members' => [Role::getRandomValue()],
            'cc_members' => [Role::getRandomValue()],
            'attachfiles' => [$attach1]
        ]);

    // Assert
    $response->assertStatus(302)
        ->assertSessionHasNoErrors();
    // find attached file
    $mal = Message::where('title', 'testmessage1')->first()->message_attachments->first()->location;
    Storage::disk('public')->assertExists($mal);

});
it('can create a message with three attachments', function () {
    // Arrange
    $region = Region::where('code', 'HBVDA')->first();
    $attach1 = UploadedFile::fake()->create('apdf1.pdf', 70);
    $attach2 = UploadedFile::fake()->create('apdf2.pdf', 80);
    $attach3 = UploadedFile::fake()->create('apdf3.pdf', 90);

    // Act
    $response = $this->authenticated()
        ->post(route('message.store', ['region' => $region, 'user' => $region->users()->first()]), [
            'title' => 'testmessage2',
            'body' => 'this is a test',
            'greeting' => 'hello',
            'salutation' => 'all',
            'send_at' => now(),
            'delete_at' => now()->addDays(3),
            'to_members' => [Role::getRandomValue()],
            'cc_members' => [Role::getRandomValue()],
            'attachfiles' => [$attach1, $attach2, $attach3]
        ]);

    // Assert
    $response->assertStatus(302)
        ->assertSessionHasNoErrors();
    // find attached file
    foreach (Message::where('title', 'testmessage2')->first()->message_attachments as $ma) {
        Storage::disk('public')->assertExists($ma->location);
    }
});
it('cannot create a message with four attachments', function () {
    // Arrange
    $region = Region::where('code', 'HBVDA')->first();
    $attach1 = UploadedFile::fake()->create('apdf1.pdf', 70);
    $attach2 = UploadedFile::fake()->create('apdf2.xlsx', 80);
    $attach3 = UploadedFile::fake()->create('apdf3.pdf', 90);
    $attach4 = UploadedFile::fake()->create('apdf3.pdf', 100);

    // Act
    $response = $this->authenticated()
        ->post(route('message.store', ['region' => $region, 'user' => $region->users()->first()]), [
            'title' => 'testmessage3',
            'body' => 'this is a test',
            'greeting' => 'hello',
            'salutation' => 'all',
            'send_at' => now(),
            'delete_at' => now()->addDays(3),
            'to_members' => [Role::getRandomValue()],
            'cc_members' => [Role::getRandomValue()],
            'attachfiles' => [$attach1, $attach2, $attach3, $attach4]
        ]);

    // Assert
    $response->assertStatus(302)
        ->assertSessionHasErrors();
});
it('cannot create a message with an attachment >5MB', function () {
    // Arrange
    $region = Region::where('code', 'HBVDA')->first();
    $attach1 = UploadedFile::fake()->create('apdf.pdf', 6000);

    // Act
    $response = $this->authenticated()
        ->post(route('message.store', ['region' => $region, 'user' => $region->users()->first()]), [
            'title' => 'testmessage1',
            'body' => 'this is a test',
            'greeting' => 'hello',
            'salutation' => 'all',
            'send_at' => now(),
            'delete_at' => now()->addDays(3),
            'to_members' => [Role::getRandomValue()],
            'cc_members' => [Role::getRandomValue()],
            'attachfiles' => [$attach1]
        ]);

    // Assert
    $response->assertStatus(302)
        ->assertSessionHasErrors();

});
it('cannot create a message with a txt attachment', function () {
    // Arrange
    $region = Region::where('code', 'HBVDA')->first();
    $attach1 = UploadedFile::fake()->create('apdf.txt', 60);

    // Act
    $response = $this->authenticated()
        ->post(route('message.store', ['region' => $region, 'user' => $region->users()->first()]), [
            'title' => 'testmessage1',
            'body' => 'this is a test',
            'greeting' => 'hello',
            'salutation' => 'all',
            'send_at' => now(),
            'delete_at' => now()->addDays(3),
            'to_members' => [Role::getRandomValue()],
            'cc_members' => [Role::getRandomValue()],
            'attachfiles' => [$attach1]
        ]);

    // Assert
    $response->assertStatus(302)
        ->assertSessionHasErrors();

});
