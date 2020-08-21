<?php

use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;

return [

    LeagueAgeType::class => [
        LeagueAgeType::Senior => 'Senior',
        LeagueAgeType::Junior => 'Junior',
        LeagueAgeType::Mini => 'Minis',
    ],

    LeagueGenderType::class => [
        LeagueGenderType::Male => 'male',
        LeagueGenderType::Female => 'female',
        LeagueGenderType::Mixed => 'mixed',
    ],


];
