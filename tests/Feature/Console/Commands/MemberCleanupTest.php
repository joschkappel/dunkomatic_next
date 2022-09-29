<?php

use App\Models\Member;

it('does not find duplicate members by email1', function () {
    // Arrange

    // Act and Assert
    $this->artisan('dmatic:membercleanup --count=2')
        ->expectsQuestion('Which key shall I use to detect duplicates?', 'email1')
        ->expectsOutput('No duplicates found for email1')
        ->assertSuccessful();
});
it('finds duplicate members by email1,lastname and merges', function () {
    // Arrange
    $mem_a = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    $mem_b = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    $m_cnt = Member::count();

    // Act and Assert
    $this->artisan('dmatic:membercleanup --count=2')
        ->expectsQuestion('Which key shall I use to detect duplicates?', 'email1 lastname')
        ->expectsOutput('Found 1 members with 2 email1 lastname')
        ->expectsQuestion('Which member do you want to keep?', $mem_a->id.': '.$mem_a->name)
        ->expectsQuestion('Copy firstname '.$mem_b->firstname.' -> '.$mem_a->firstname.'?', 'yes')
        ->expectsQuestion('Copy city '.$mem_b->city.' -> '.$mem_a->city.'?', 'yes')
        ->expectsQuestion('Copy zipcode '.$mem_b->zipcode.' -> '.$mem_a->zipcode.'?', 'yes')
        ->expectsQuestion('Copy street '.$mem_b->street.' -> '.$mem_a->street.'?', 'yes')
        ->expectsQuestion('Copy mobile '.$mem_b->mobile.' -> '.$mem_a->mobile.'?', 'yes')
        ->assertSuccessful();

    $this->assertDatabaseHas('members', ['lastname' => 'A'])
        ->assertDatabaseCount('members', $m_cnt - 1);

    // Clean
    Member::where('lastname', 'A')->delete();
});
it('finds duplicate members by email and merges', function () {
    // Arrange
    $mem_a = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    $mem_b = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    $m_cnt = Member::count();

    // Act and Assert
    $this->artisan('dmatic:membercleanup --count=2')
        ->expectsQuestion('Which key shall I use to detect duplicates?', 'email1')
        ->expectsOutput('Found 1 members with 2 email1')
        ->expectsQuestion('Which member do you want to keep?', $mem_a->id.': '.$mem_a->name)
        ->expectsQuestion('Copy firstname '.$mem_b->firstname.' -> '.$mem_a->firstname.'?', 'yes')
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
    $this->artisan('dmatic:membercleanup --count=2')
        ->expectsQuestion('Which key shall I use to detect duplicates?', 'firstname lastname')
        ->expectsOutput('Found 1 members with 2 firstname lastname')
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
