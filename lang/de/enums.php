<?php

use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;
use App\Enums\Role;
use App\Enums\JobFrequencyType;
use App\Enums\LeagueState;

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
      Role::ClubLead => 'Abteilungsleitung',
      Role::RefereeLead => 'Schiedsrichterwart:in',
      Role::LeagueLead => 'Staffelleitung',
      Role::RegionTeam => 'Bezirksmitarbeiter:in',
      Role::GirlsLead => 'Verantwtl. Mädchenbasket',
      Role::JuniorsLead => 'Jugendwart:in',
      Role::RegionLead => 'Bezirksleitung'
    ],

    JobFrequencyType::class => [
      JobFrequencyType::never => 'nie',
      JobFrequencyType::daily => 'täglich',
      JobFrequencyType::weekly => 'wöchentlich',
      JobFrequencyType::biweekly => 'zweiwöchentlich',
      JobFrequencyType::monthly => 'monatlich'
    ],

    LeagueState::class => [
      LeagueState::Setup =>  'Spielrunde wird definiert',
      LeagueState::Registration =>  'Mannschaftsmeldung',
      LeagueState::Selection =>   'Verein wählt Ziffern',
      LeagueState::Scheduling => 'Verein legt Heimspieltermine fest',
      LeagueState::Live => 'Live',
      LeagueState::Freeze => 'Wartet auf Freigabe',
      LeagueState::Referees => 'Bezirk legt Schiedsrichter fest',
    ]

];
