<?php

return [
    'title' => 'dunk🏀matic',
    'title_prefix' => '',
    'title_postfix' => 'Next',
    'welcome' => 'All you need to get the Ball rolling',

    'use_ico_only' => true,
    'use_full_favicon' => true,

    'table_page_length' => 50, // 10,20,50,100

    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'profile_url' => 'admin.user.show',
    'password_reset_url' => 'password.reset',
    'password_email_url' => 'password.email',

    'enabled_laravel_mix' => true,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    'grafics' => [
        'welcome_l' => 'welcome_landscape.jpg',
        'welcome_p' => 'welcome_portrait.jpg',
        'club' => 'club.jpg',
        'league' => 'league.jpg',
        'region' => 'region.jpg',
        '403' => '403_forbidden.jpg',
        '404' => '404_not_found.jpg',
        '503' => '503_service_unavailable.jpg',
        '419' => '419_timeout.jpg',
    ],

    'maps_uri' => 'https://www.google.com/maps/place/',

    /*
  |--------------------------------------------------------------------------
  | allowed characters for league schemes (A to Q without J) or 1-16
  |--------------------------------------------------------------------------
  */
    'league_team_chars' => [1 => 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'K', 'L', 'M', 'N', 'O', 'P', 'Q'],
    /*

  /*
  |--------------------------------------------------------------------------
  | allowed characters for gym numbering
  |--------------------------------------------------------------------------
  */
    'allowed_gym_nos' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],

    'folders' => [
        'export' => 'exports',
        'backup' => 'backups',
    ],
    'export_folders' => [
        'leagues' => 'runden',
        'clubs' => 'vereine',
        'teamware' => 'teamware',
        'archive' => 'archive',
    ],

    'db_backup_age' => 30, // keep db backups for 30 days

];
