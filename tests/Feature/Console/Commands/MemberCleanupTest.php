<?php

it('finds duplicate members, none merged', function () {
    // Arrange

    // Act and Assert
    $this->artisan('dmatic:membercleanup')
        ->expectsQuestion('Which member do you want to keep?', 'all')
        ->doesntExpectOutput('Do you want to copy properties?')
        ->assertSuccessful();
});
