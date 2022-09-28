<?php

dataset('dates', function () {
    return [
        '1 week before' => now()->subWeeks(1),
        'now' => now(),
        '1 week after' => now()->addWeeks(1),
    ];
});
