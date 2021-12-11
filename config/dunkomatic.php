<?php

return [
    'title' => 'DunkOmatic',
    'title_prefix' => '',
    'title_postfix' => 'Next',

    'use_ico_only' => false,
    'use_full_favicon' => true,

    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'profile_url' => 'admin.user.show',
    'password_reset_url' => 'password.reset',
    'password_email_url' => 'password.email',

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    'grafics' => array(
        'welcome' => 'welcome.jpg',
        'club'    => 'club.jpg',
        'league'    => 'league.jpg',
        'region'    => 'region.jpg',
        '403'     => '403_forbidden.jpg',
        '404'     => '404_not_found.jpg',
        '503'     => '503_service_unavailable.jpg',
        '419'     => '419_timeout.jpg'
    ),

    'maps_uri' => 'https://www.google.com/maps/place/',

    /*
  |--------------------------------------------------------------------------
  | allowed characters for league schemes (A to Q without J) or 1-16
  |--------------------------------------------------------------------------
  */
    'league_team_chars' => array(1 => 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'K', 'L', 'M', 'N', 'O', 'P', 'Q'),
    /*

  /*
  |--------------------------------------------------------------------------
  | allowed characters for gym numbering
  |--------------------------------------------------------------------------
  */
    'allowed_gym_nos' => array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10"),
    /*

  |--------------------------------------------------------------------------
  | define coloring for leagues
  | color => [  above_region ( 0=false, 1=true),
  |             gender (0=male, 1=female, 2=mixed),
  |             agetype (0=senior, 1=junior, 2=mini)
  |           ]
  |--------------------------------------------------------------------------
  */
    'league_colors' => array(
        '000' => 'red',
        '010' => 'pink',
        '020' => 'purple',
        '001' => 'deep purple',
        '011' => 'indigo',
        '021' => 'blue',
        '002' => 'light blue',
        '012' => 'cyan',
        '022' => 'teal',
        '100' => 'green',
        '110' => 'light green',
        '120' => 'lime',
        '101' => 'yellow',
        '111' => 'amber',
        '121' => 'orange',
        '102' => 'deep orange',
        '112' => 'brown',
        '122' => 'gray',
        '0' => 'white',
        '1' => 'white',
    ),

    'report_folder_leagues' => 'runden',
    'report_folder_clubs' => 'vereine',
    'report_folder_teamware' => 'teamware',

];
