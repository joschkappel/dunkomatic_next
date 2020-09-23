<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Region;
use Faker\Generator as Faker;

$factory->define(Region::class, function (Faker $faker) {
    return [
        'name' => 'Hessischer Basketball Verband',
        'code' => 'HBV',
        'hq' => '',
        'created_at' => now()
    ];
});
