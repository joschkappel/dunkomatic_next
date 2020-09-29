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
  'password_reset_url' => 'password.reset',
  'password_email_url' => 'password.email',

  'enabled_laravel_mix' => false,
  'laravel_mix_css_path' => 'css/app.css',
  'laravel_mix_js_path' => 'js/app.js',
  'plugins' => [],

  /*
  |--------------------------------------------------------------------------
  | allowed characters for league schemes (A to Q without J) or 1-16
  |--------------------------------------------------------------------------
  |
  |
  */

  'league_team_chars' => array( 1 => 'A','B','C','D','E','F','G','H','I','K','L','M','N','O','P','Q'),
  'report_folder_leagues' => 'runden',
  'report_folder_clubs' => 'vereine',
];
