<?php

use App\Models\User;

it('does not find the user', function () {
    $this->artisan('dmatic:usermove 1000')
        ->expectsOutput('There is no user with ID 1000 !')
        ->assertFailed();
});
it('finds the admin user', function () {
    $this->artisan('dmatic:usermove 1')
        ->expectsOutput('Targeting user admin')
        ->expectsOutput('Cannot move users that are assigned to more than one region!')
        ->assertFailed();
});
it('finds the target user', function () {
    $this->artisan('dmatic:usermove 6')
        ->expectsOutput('Targeting user approved')
        ->expectsQuestion('Please select the target region', 'HBVF')
        ->expectsOutput('Moved user approved from region HBVDA to region HBVF')
        ->assertExitCode(0);

    $u = User::find(6)->regions()->first();
    $this->assertTrue($u->code == 'HBVF');
    $this->assertFalse($u->code == 'HBVDA');
});
