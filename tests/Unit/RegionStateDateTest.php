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
            'job_noleads' => JobFrequencyType::weekly(),
            'job_game_notime' => JobFrequencyType::weekly(),
            'job_game_overlaps' => JobFrequencyType::weekly(),
            'job_email_valid' => JobFrequencyType::weekly(),
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
            'job_noleads' => JobFrequencyType::weekly(),
            'job_game_notime' => JobFrequencyType::weekly(),
            'job_game_overlaps' => JobFrequencyType::weekly(),
            'job_email_valid' => JobFrequencyType::weekly(),
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
            'job_noleads' => JobFrequencyType::weekly(),
            'job_game_notime' => JobFrequencyType::weekly(),
            'job_game_overlaps' => JobFrequencyType::weekly(),
            'job_email_valid' => JobFrequencyType::weekly(),
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
            'job_noleads' => JobFrequencyType::weekly(),
            'job_game_notime' => JobFrequencyType::weekly(),
            'job_game_overlaps' => JobFrequencyType::weekly(),
            'job_email_valid' => JobFrequencyType::weekly(),
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
            'job_noleads' => JobFrequencyType::weekly(),
            'job_game_notime' => JobFrequencyType::weekly(),
            'job_game_overlaps' => JobFrequencyType::weekly(),
            'job_email_valid' => JobFrequencyType::weekly(),
            'close_referees_at' => $phasedate, ]);

    // Assert
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
})->with('dates');
