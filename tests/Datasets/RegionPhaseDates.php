<?php

dataset('regionphasedates', function () {
    return [
        'all same' => [now(), now(), now(), now(), now()],
        'all +1 week diff' => [now(), now()->addWeeks(1), now()->addWeeks(2), now()->addWeeks(3), now()->addWeeks(4)],
        'all -1 week diff' => [now(), now()->subWeeks(1), now()->subWeeks(2), now()->subWeeks(3), now()->subWeeks(4)],
        '2nd -1 week diff' => [now(), now()->subWeeks(1), now()->addWeeks(2), now()->addWeeks(3), now()->addWeeks(4)],
        '3rd -1 week diff' => [now(), now()->addWeeks(1), now()->subWeeks(2), now()->addWeeks(3), now()->addWeeks(4)],
    ];
});
