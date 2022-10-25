<?php

use App\Enums\JobFrequencyType;
use App\Models\Region;

it('updates all phase dates', function ($open_selection, $close_selection, $open_scheduling, $close_scheduling, $close_refs) {
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
            'open_selection_at' => $open_selection,
            'close_selection_at' => $close_selection,
            'open_scheduling_at' => $open_scheduling,
            'close_scheduling_at' => $close_scheduling,
            'close_referees_at' => $close_refs, ]);

    // Assert
    $response->assertRedirect();
    if (($open_selection->toDateString() < $close_selection->toDateString()) and
         ($close_selection->toDateString() < $open_scheduling->toDateString()) and
         ($open_scheduling->toDateString() < $close_scheduling->toDateString()) and
         ($close_scheduling->toDateString() < $close_refs->toDateString())) {
        $response->assertSessionHasNoErrors();
    } else {
        $response->assertSessionHasErrors();
    }
})->with('regionphasedates');

it('updates historic phase dates', function ($open_selection, $close_selection, $open_scheduling, $close_scheduling, $close_refs) {
    // Arrange
    $region = Region::first();
    $this->travel(6)->weeks();

    // Act
    $response = $this->authenticated()
        ->put(route('region.update_details', ['region' => $region->id]), [
            'name' => 'datetestregion',
            'game_slot' => 120,
            'job_noleads' => JobFrequencyType::weekly(),
            'job_game_notime' => JobFrequencyType::weekly(),
            'job_game_overlaps' => JobFrequencyType::weekly(),
            'job_email_valid' => JobFrequencyType::weekly(),
            'open_selection_at' => $open_selection,
            'close_selection_at' => $close_selection,
            'open_scheduling_at' => $open_scheduling,
            'close_scheduling_at' => $close_scheduling,
            'close_referees_at' => $close_refs, ]);

    // Assert
    $response->assertRedirect();
    if (($open_selection->toDateString() < $close_selection->toDateString()) and
         ($close_selection->toDateString() < $open_scheduling->toDateString()) and
         ($open_scheduling->toDateString() < $close_scheduling->toDateString()) and
         ($close_scheduling->toDateString() < $close_refs->toDateString())) {
        $response->assertSessionHasNoErrors();
    } else {
        $response->assertSessionHasErrors();
    }
})->with('regionphasedates');
