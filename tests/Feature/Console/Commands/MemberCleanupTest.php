<?php

use App\Models\Member;

it('finds duplicate members by lastname and merges', function () {
    // Arrange
    $mem_a = Member::factory()->create(['lastname' => 'A']);
    $mem_b = Member::factory()->create(['lastname' => 'A']);
    $m_cnt = Member::count();

    // Act and Assert
    $this->artisan('dmatic:membercleanup')
        ->expectsQuestion('Which key shall I use to detect duplicates?', 'lastname')
        ->expectsOutput('Found 1 members with duplicate lastname')
        ->expectsQuestion('Which member do you want to keep?', $mem_a->id.': '.$mem_a->name)
        ->expectsQuestion('Copy firstname '.$mem_b->firstname.' -> '.$mem_a->firstname.'?', 'yes')
        ->expectsQuestion('Copy city '.$mem_b->city.' -> '.$mem_a->city.'?', 'yes')
        ->expectsQuestion('Copy zipcode '.$mem_b->zipcode.' -> '.$mem_a->zipcode.'?', 'yes')
        ->expectsQuestion('Copy street '.$mem_b->street.' -> '.$mem_a->street.'?', 'yes')
        ->expectsQuestion('Copy mobile '.$mem_b->mobile.' -> '.$mem_a->mobile.'?', 'yes')
        ->expectsQuestion('Copy email1 '.$mem_b->email1.' -> '.$mem_a->email1.'?', 'yes')
        ->assertSuccessful();

    $this->assertDatabaseHas('members', ['lastname' => 'A'])
        ->assertDatabaseCount('members', $m_cnt - 1);

    // Clean
    Member::where('lastname', 'A')->delete();
});
it('finds duplicate members by email and merges', function () {
    // Arrange
    $mem_a = Member::factory()->create(['email1' => 'A@gmail.com']);
    $mem_b = Member::factory()->create(['email1' => 'A@gmail.com']);
    $m_cnt = Member::count();

    // Act and Assert
    $this->artisan('dmatic:membercleanup')
        ->expectsQuestion('Which key shall I use to detect duplicates?', 'email1')
        ->expectsOutput('Found 1 members with duplicate email1')
        ->expectsQuestion('Which member do you want to keep?', $mem_a->id.': '.$mem_a->name)
        ->expectsQuestion('Copy firstname '.$mem_b->firstname.' -> '.$mem_a->firstname.'?', 'yes')
        ->expectsQuestion('Copy lastname '.$mem_b->lastname.' -> '.$mem_a->lastname.'?', 'yes')
        ->expectsQuestion('Copy city '.$mem_b->city.' -> '.$mem_a->city.'?', 'yes')
        ->expectsQuestion('Copy zipcode '.$mem_b->zipcode.' -> '.$mem_a->zipcode.'?', 'yes')
        ->expectsQuestion('Copy street '.$mem_b->street.' -> '.$mem_a->street.'?', 'yes')
        ->expectsQuestion('Copy mobile '.$mem_b->mobile.' -> '.$mem_a->mobile.'?', 'yes')
        ->assertSuccessful();

    $this->assertDatabaseHas('members', ['email1' => 'A@gmail.com'])
        ->assertDatabaseCount('members', $m_cnt - 1);

    // Clean
    Member::where('email1', 'A@gmail.com')->delete();
});
it('finds duplicate members by firstname,lastname and merges', function () {
    // Arrange
    $mem_a = Member::factory()->create(['lastname' => 'A', 'firstname' => 'B']);
    $mem_b = Member::factory()->create(['lastname' => 'A', 'firstname' => 'B']);
    $m_cnt = Member::count();

    // Act and Assert
    $this->artisan('dmatic:membercleanup')
        ->expectsQuestion('Which key shall I use to detect duplicates?', 'firstname lastname')
        ->expectsOutput('Found 1 members with duplicate firstname lastname')
        ->expectsQuestion('Which member do you want to keep?', $mem_a->id.': '.$mem_a->name)
        ->expectsQuestion('Copy city '.$mem_b->city.' -> '.$mem_a->city.'?', 'yes')
        ->expectsQuestion('Copy zipcode '.$mem_b->zipcode.' -> '.$mem_a->zipcode.'?', 'yes')
        ->expectsQuestion('Copy street '.$mem_b->street.' -> '.$mem_a->street.'?', 'yes')
        ->expectsQuestion('Copy mobile '.$mem_b->mobile.' -> '.$mem_a->mobile.'?', 'yes')
        ->expectsQuestion('Copy email1 '.$mem_b->email1.' -> '.$mem_a->email1.'?', 'yes')
        ->assertSuccessful();

    $this->assertDatabaseHas('members', ['lastname' => 'A', 'firstname' => 'B'])
        ->assertDatabaseCount('members', $m_cnt - 1);

    // Clean
    Member::where('lastname', 'A')->delete();
});
