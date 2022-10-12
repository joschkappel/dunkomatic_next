<?php

use App\Enums\JobFrequencyType;
use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;
use App\Enums\LeagueState;
use App\Enums\Report;
use App\Enums\Role;

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
        Role::ClubLead => 'Club lead',
        Role::RefereeLead => 'Referee lead',
        Role::LeagueLead => 'League lead',
        Role::RegionTeam => 'Region team',
        Role::GirlsLead => 'Girls lead',
        Role::JuniorsLead => 'Juniors lead',
        Role::RegionLead => 'Region lead',
        Role::TeamCoach => 'Team Coach',
    ],
    JobFrequencyType::class => [
        JobFrequencyType::never => 'never',
        JobFrequencyType::daily => 'daily',
        JobFrequencyType::weekly => 'weekly',
        JobFrequencyType::biweekly => 'bi-weekly',
        JobFrequencyType::monthly => 'monthly',
    ],

    LeagueState::class => [
        LeagueState::Setup => 'Setup League',
        LeagueState::Registration => 'Register Teams',
        LeagueState::Selection => 'Select Team Number',
        LeagueState::Scheduling => 'Schedule Home Games',
        LeagueState::Live => 'Live',
        LeagueState::Freeze => 'Waiting',
        LeagueState::Referees => 'Pick Referees',
    ],

    Report::class => [
        Report::AddressBook => 'Address Book',
        Report::Teamware => 'Teamware Imports',
        Report::LeagueBook => 'Leagues Book',
        Report::RegionGames => 'Regionplan',
        Report::ClubGames => 'Clubplan',
        Report::LeagueGames => 'Leagueplan',
    ],
];
