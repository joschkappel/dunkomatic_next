<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
 /*
  |--------------------------------------------------------------------------
  | define coloring for leagues
  | color => [  above_region ( 0=false, 1=true),
  |             gender (0=male, 1=female, 2=mixed),
  |             agetype (0=senior, 1=junior, 2=mini)
  |           ]
  |--------------------------------------------------------------------------
  */

/**
 */
final class LeagueColor extends Enum
{
    const red =    [false, LeagueAgeType::Senior, LeagueGenderType::Male];
    const pink =   [false, LeagueAgeType::Junior, LeagueGenderType::Male];
    const purple =   [false, LeagueAgeType::Mini, LeagueGenderType::Male];
    const MediumPurple =  [false, LeagueAgeType::Senior, LeagueGenderType::Female];
    const indigo =   [false, LeagueAgeType::Junior, LeagueGenderType::Female];
    const blue =   [false, LeagueAgeType::Mini, LeagueGenderType::Female];
    const lightblue =  [false, LeagueAgeType::Senior, LeagueGenderType::Mixed];
    const cyan =   [false, LeagueAgeType::Junior, LeagueGenderType::Mixed];
    const teal =   [false, LeagueAgeType::Mini, LeagueGenderType::Mixed];
    const green =    [true, LeagueAgeType::Senior, LeagueGenderType::Male];
    const LightGreen =   [true, LeagueAgeType::Junior, LeagueGenderType::Male];
    const lime =   [true, LeagueAgeType::Mini, LeagueGenderType::Male];
    const yellow =  [true, LeagueAgeType::Senior, LeagueGenderType::Female];
    const amber =   [true, LeagueAgeType::Junior, LeagueGenderType::Female];
    const orange =   [true, LeagueAgeType::Mini, LeagueGenderType::Female];
    const DarkOrange =  [true, LeagueAgeType::Senior, LeagueGenderType::Mixed];
    const brown =   [true, LeagueAgeType::Junior, LeagueGenderType::Mixed];
    const gray =   [true, LeagueAgeType::Mini, LeagueGenderType::Mixed];
}
