<?php

use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;
use App\Enums\Role;
use App\Enums\JobFrequencyType;

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

    Role::class => [
      Role::User => 'User',
      Role::ClubLead => 'Club lead',
      Role::RefereeLead => 'Referee lead',
      Role::LeagueLead => 'League lead',
      Role::RegionTeam => 'Region team',
      Role::GirlsLead => 'Girls lead',
      Role::JuniorsLead => 'Juniors lead',
      Role::RegionLead => 'Region lead',
      Role::Admin => 'Admin'
    ],
    JobFrequencyType::class => [
      JobFrequencyType::never => 'never',
      JobFrequencyType::daily => 'daily',
      JobFrequencyType::weekly => 'weekly',
      JobFrequencyType::biweekly => 'bi-weekly',
      JobFrequencyType::monthly => 'monthly'
    ]
];
