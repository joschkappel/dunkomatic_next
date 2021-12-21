<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
 /*
  |--------------------------------------------------------------------------
  | define coloring for schedules and events
  | color => [  top_region ( 0=false, 1=true),
  |             size  (4,6,8,10,12,14,16),
  |             iterations (1,2+x)
  |           ]
  |--------------------------------------------------------------------------
  */

/**
 */
final class ScheduleColor extends Enum
{
    const red =    [false, 4, 1];
    const orange = [false, 4, 2];
    const tomato = [false, 4, 3];
    const pink =   [false, 6, 1];
    const DarkOrange = [false, 6, 2];
    const crimson = [false, 6, 3];
    const purple =   [false, 8, 1];
    const MediumPurple =  [false, 10, 1];
    const indigo =   [false, 12, 1];
    const blue =   [false, 14, 1];
    const LightBlue =  [false, 16, 1];
    const cyan =   [true, 4, 1];
    const teal =   [true, 6, 1];
    const green =    [true, 8, 1];
    const olive =   [true, 10, 1];
    const lime =   [true, 12, 1];
    const yellow =  [true, 14, 1];
    const amber =   [true, 16, 1];
    const gray = [false, 0 , 1];
    const brown = [true, 0 , 1];
}
