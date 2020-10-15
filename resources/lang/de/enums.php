<?php

use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;

return [

    LeagueAgeType::class => [
        LeagueAgeType::Senior => 'Senioren',
        LeagueAgeType::Junior => 'Jugend',
        LeagueAgeType::Mini => 'Minis',
    ],

    LeagueGenderType::class => [
        LeagueGenderType::Male => 'männlich',
        LeagueGenderType::Female => 'weiblich',
        LeagueGenderType::Mixed => 'mixed',
    ],

    Role::class => [
      Role::User => 'Benutzer',
      Role::ClubLead => 'Abteilungsleiter',
      Role::RefereeLead => 'Schiedsrichterwart',
      Role::LeagueLead => 'Staffelleiter',
      Role::RegionTeam => 'Bezirksmitarbeiter',
      Role::GirlsLead => 'Verantwtl. Mädchenbasket',
      Role::JuniorsLead => 'Jugendwart',
      Role::RegionLead => 'Bezirskvorstand',
      Role::Admin => 'Systemverwalter'
    ],
];
