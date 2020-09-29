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


  'plugins' => [
     'Datatables' => [
          'name' => 'Datatables',
          'active' => false,
          'files' => [
              [
                  'type' => 'js',
                  'asset' => false,
                  'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
              ],
              [
                  'type' => 'js',
                  'asset' => false,
                  'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
              ],
              [
                  'type' => 'css',
                  'asset' => false,
                  'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
              ],
          ],
      ],
      'Select2' => [
          'name' => 'Select2',
          'active' => true,
          'files' => [
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
              ],
              [
                  'type' => 'css',
                  'asset' => true,
                  'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
              ],
          ],
      ],
      'Chartjs' => [
          'name' => 'Chartjs',
          'active' => false,
          'files' => [
              [
                  'type' => 'js',
                  'asset' => false,
                  'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
              ],
          ],
      ],
      'Sweetalert2' => [
          'name' => 'Sweetalert2',
          'active' => false,
          'files' => [
              [
                  'type' => 'js',
                  'asset' => false,
                  'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
              ],
          ],
      ],
      'Pace' => [
          'name' => 'Pace',
          'active' => false,
          'files' => [
              [
                  'type' => 'css',
                  'asset' => false,
                  'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
              ],
              [
                  'type' => 'js',
                  'asset' => false,
                  'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
              ],
          ],
      ],
  ],
];
