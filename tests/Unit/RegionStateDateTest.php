<?php

use App\Enums\JobFrequencyType;
use App\Models\Region;

it('update open_selection', function ($phasedate) {
    // Arrange
    $region = Region::first();

    // Act
    $response = $this->authenticated()
        ->put(route('region.update_details', ['region' => $region->id]), [
            'name' => 'datetestregion',
            'game_slot' => 120,
            'job_noleads' => JobFrequencyType::never(),
            'job_game_notime' => JobFrequencyType::never(),
            'job_game_overlaps' => JobFrequencyType::never(),
            'job_email_valid' => JobFrequencyType::never(),
            'open_selection_at' => $phasedate, ]);

    // Assert
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
})->with('dates');

it('update close_selection', function ($phasedate) {
    // Arrange
    $region = Region::first();

    // Act
    $response = $this->authenticated()
        ->put(route('region.update_details', ['region' => $region->id]), [
            'name' => 'datetestregion',
            'game_slot' => 120,
            'job_noleads' => JobFrequencyType::never(),
            'job_game_notime' => JobFrequencyType::never(),
            'job_game_overlaps' => JobFrequencyType::never(),
            'job_email_valid' => JobFrequencyType::never(),
            'close_selection_at' => $phasedate, ]);

    // Assert
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
})->with('dates');

it('update open_scheduling', function ($phasedate) {
    // Arrange
    $region = Region::first();

    // Act
    $response = $this->authenticated()
        ->put(route('region.update_details', ['region' => $region->id]), [
            'name' => 'datetestregion',
            'game_slot' => 120,
            'job_noleads' => JobFrequencyType::never(),
            'job_game_notime' => JobFrequencyType::never(),
            'job_game_overlaps' => JobFrequencyType::never(),
            'job_email_valid' => JobFrequencyType::never(),
            'open_scheduling_at' => $phasedate, ]);

    // Assert
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
})->with('dates');

it('update close_scheduling', function ($phasedate) {
    // Arrange
    $region = Region::first();

    // Act
    $response = $this->authenticated()
        ->put(route('region.update_details', ['region' => $region->id]), [
            'name' => 'datetestregion',
            'game_slot' => 120,
            'job_noleads' => JobFrequencyType::never(),
            'job_game_notime' => JobFrequencyType::never(),
            'job_game_overlaps' => JobFrequencyType::never(),
            'job_email_valid' => JobFrequencyType::never(),
            'close_scheduling_at' => $phasedate, ]);

    // Assert
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
})->with('dates');

it('update close_referees', function ($phasedate) {
    // Arrange
    $region = Region::first();

    // Act
    $response = $this->authenticated()
        ->put(route('region.update_details', ['region' => $region->id]), [
            'name' => 'datetestregion',
            'game_slot' => 120,
            'job_noleads' => JobFrequencyType::never(),
            'job_game_notime' => JobFrequencyType::never(),
            'job_game_overlaps' => JobFrequencyType::never(),
            'job_email_valid' => JobFrequencyType::never(),
            'close_referees_at' => $phasedate, ]);

    // Assert
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
})->with('dates');
