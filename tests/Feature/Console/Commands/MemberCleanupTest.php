<?php

use App\Enums\Role;
use App\Models\Member;
use App\Models\Region;

it('does not find duplicate members by email1', function () {
    // Arrange

    // Act and Assert
    $this->artisan('dmatic:membercleanup --count=2')
        ->expectsQuestion('Which key shall I use to detect duplicates?', 'email1')
        ->expectsOutput('No duplicates found for email1. You may try with another key.')
        ->assertSuccessful();
});
it('finds duplicate members by email1,lastname and merges', function () {
    // Arrange
    $mem_a = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    $mem_b = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    Region::first()->memberships()->create([
        'role_id' => Role::RegionLead,
        'member_id' => $mem_b->id,
    ]);
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

    $this->assertDatabaseHas('members', ['lastname' => 'A']);
    $this->assertModelExists($mem_a);
    $this->assertTrue($mem_a->memberships()->exists());
    $this->assertModelMissing($mem_b);

    // Clean
    Member::where('lastname', 'A')->delete();
});

it('finds duplicate members by firstname,lastname and merges', function () {
    // Arrange
    $mem_a = Member::factory()->create(['lastname' => 'A', 'firstname' => 'B']);
    $mem_b = Member::factory()->create(['lastname' => 'A', 'firstname' => 'B']);
    Region::first()->memberships()->create([
        'role_id' => Role::RegionLead,
        'member_id' => $mem_b->id,
    ]);

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

    $this->assertDatabaseHas('members', ['lastname' => 'A', 'firstname' => 'B']);
    $this->assertModelExists($mem_a);
    $this->assertTrue($mem_a->memberships()->exists());
    $this->assertModelMissing($mem_b);

    // Clean
    Member::where('lastname', 'A')->delete();
});
it('finds duplicate members by email and merges first member', function () {
    // Arrange
    $mem_a = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    Region::first()->memberships()->create([
        'role_id' => Role::RegionLead,
        'member_id' => $mem_a->id,
    ]);
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

    $this->assertDatabaseHas('members', ['email1' => 'A@gmail.com']);
    $this->assertModelExists($mem_a);
    $this->assertTrue($mem_a->memberships()->exists());
    $this->assertModelMissing($mem_b);

    // Clean
    Member::where('email1', 'A@gmail.com')->delete();
});
it('finds duplicate members by email and merges last member', function () {
    // Arrange
    $mem_a = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    Region::first()->memberships()->create([
        'role_id' => Role::RegionLead,
        'member_id' => $mem_a->id,
    ]);
    $mem_b = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);

    $m_cnt = Member::count();

    // Act and Assert
    $this->artisan('dmatic:membercleanup --count=2')
        ->expectsQuestion('Which key shall I use to detect duplicates?', 'email1')
        ->expectsOutput('Found 1 members with 2 email1')
        ->expectsQuestion('Which member do you want to keep?', $mem_b->id.': '.$mem_b->name)
        ->expectsQuestion('Copy firstname '.$mem_a->firstname.' -> '.$mem_b->firstname.'?', 'yes')
        ->expectsQuestion('Copy city '.$mem_a->city.' -> '.$mem_b->city.'?', 'yes')
        ->expectsQuestion('Copy zipcode '.$mem_a->zipcode.' -> '.$mem_b->zipcode.'?', 'yes')
        ->expectsQuestion('Copy street '.$mem_a->street.' -> '.$mem_b->street.'?', 'yes')
        ->expectsQuestion('Copy mobile '.$mem_a->mobile.' -> '.$mem_b->mobile.'?', 'yes')
        ->assertSuccessful();

    $this->assertDatabaseHas('members', ['email1' => 'A@gmail.com']);
    $this->assertModelExists($mem_b);
    $this->assertTrue($mem_b->memberships()->exists());
    $this->assertModelMissing($mem_a);

    // Clean
    Member::where('email1', 'A@gmail.com')->delete();
});
it('does not find triple members by email1', function () {
    // Arrange
    $mem_a = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    $mem_b = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    $mem_c = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);

    // Act and Assert
    $this->artisan('dmatic:membercleanup --count=2')
        ->expectsQuestion('Which key shall I use to detect duplicates?', 'email1')
        ->expectsOutput('No duplicates found for email1. You may try with another key.')
        ->assertSuccessful();

    // Clean
    Member::where('email1', 'A@gmail.com')->delete();
});
it('finds triple members by email and merges first and second member', function () {
    // Arrange
    $mem_a = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    Region::first()->memberships()->create([
        'role_id' => Role::RegionLead,
        'member_id' => $mem_a->id,
    ]);
    $mem_b = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    $mem_c = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);

    // Act and Assert
    $this->artisan('dmatic:membercleanup --count=3')
        ->expectsQuestion('Which key shall I use to detect duplicates?', 'email1')
        ->expectsOutput('Found 1 members with 3 email1')
        ->expectsQuestion('Which member do you want to keep?', $mem_b->id.': '.$mem_b->name)
        ->expectsQuestion('Which member do you want to merge?', $mem_a->id.': '.$mem_a->name)
        ->expectsQuestion('Copy firstname '.$mem_a->firstname.' -> '.$mem_b->firstname.'?', 'yes')
        ->expectsQuestion('Copy city '.$mem_a->city.' -> '.$mem_b->city.'?', 'yes')
        ->expectsQuestion('Copy zipcode '.$mem_a->zipcode.' -> '.$mem_b->zipcode.'?', 'yes')
        ->expectsQuestion('Copy street '.$mem_a->street.' -> '.$mem_b->street.'?', 'yes')
        ->expectsQuestion('Copy mobile '.$mem_a->mobile.' -> '.$mem_b->mobile.'?', 'yes')
        ->assertSuccessful();

    $this->assertDatabaseHas('members', ['email1' => 'A@gmail.com']);
    $this->assertModelExists($mem_b);
    $this->assertTrue($mem_b->memberships()->exists());
    $this->assertModelExists($mem_c);
    $this->assertModelMissing($mem_a);

    // Clean
    Member::where('email1', 'A@gmail.com')->delete();
});
it('finds triple members by email and merges first and last member', function () {
    // Arrange
    $mem_a = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    Region::first()->memberships()->create([
        'role_id' => Role::RegionLead,
        'member_id' => $mem_a->id,
    ]);
    $mem_b = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);
    $mem_c = Member::factory()->create(['lastname' => 'A', 'email1' => 'A@gmail.com']);

    // Act and Assert
    $this->artisan('dmatic:membercleanup --count=3')
        ->expectsQuestion('Which key shall I use to detect duplicates?', 'email1')
        ->expectsOutput('Found 1 members with 3 email1')
        ->expectsQuestion('Which member do you want to keep?', $mem_c->id.': '.$mem_c->name)
        ->expectsQuestion('Which member do you want to merge?', $mem_a->id.': '.$mem_a->name)
        ->expectsQuestion('Copy firstname '.$mem_a->firstname.' -> '.$mem_c->firstname.'?', 'yes')
        ->expectsQuestion('Copy city '.$mem_a->city.' -> '.$mem_c->city.'?', 'yes')
        ->expectsQuestion('Copy zipcode '.$mem_a->zipcode.' -> '.$mem_c->zipcode.'?', 'yes')
        ->expectsQuestion('Copy street '.$mem_a->street.' -> '.$mem_c->street.'?', 'yes')
        ->expectsQuestion('Copy mobile '.$mem_a->mobile.' -> '.$mem_c->mobile.'?', 'yes')
        ->assertSuccessful();

    $this->assertDatabaseHas('members', ['email1' => 'A@gmail.com']);
    $this->assertModelExists($mem_b);
    $this->assertModelExists($mem_c);
    $this->assertTrue($mem_c->memberships()->exists());
    $this->assertModelMissing($mem_a);

    // Clean
    Member::where('email1', 'A@gmail.com')->delete();
});
