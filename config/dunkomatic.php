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
      'Summernote' => [
        'name' => 'Summernote',
        'active' => true,
        'files' => [
          [
            'type' => 'js',
            'asset' => true,
            'location' => 'vendor/summernote/summernote-bs4.min.js'
          ],
          [
            'type' => 'js',
            'asset' => true,
            'location' => 'vendor/summernote/lang/summernote-de-DE.min.js'
          ],
          [
            'type' => 'css',
            'asset' => true,
            'location' => 'vendor/summernote/summernote-bs4.min.css'
          ],
        ]
      ],
      'Moment' => [
         'name' => 'Moment',
         'active' => true,
         'files' => [
             [
                 'type' => 'js',
                 'asset' => true,
                 'location' => 'vendor/moment/moment-with-locales.min.js'
             ],
          ],
      ],
      'RangeSlider' => [
        'name' => 'RangeSlider',
        'active' => true,
        'files' => [
          [
            'type' => 'js',
            'asset' => true,
            'location' => 'vendor/ion-rangeslider/js/ion.rangeSlider.min.js'
          ],
          [
            'type' => 'css',
            'asset' => true,
            'location' => 'vendor/ion-rangeslider/css/ion.rangeSlider.min.css'
          ],
        ]
      ],
      'TempusDominus' => [
         'name' => 'TempusDominus',
         'active' => true,
         'files' => [
             [
                 'type' => 'js',
                 'asset' => true,
                 'location' => 'vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js'
             ],
             [
                 'type' => 'css',
                 'asset' => true,
                 'location' => 'vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css'
             ],
          ],
      ],
      'DateRangePicker' => [
         'name' => 'DateRangePicker',
         'active' => true,
         'files' => [
             [
                 'type' => 'js',
                 'asset' => true,
                 'location' => 'vendor/moment/moment-with-locales.min.js'
             ],
             [
                 'type' => 'js',
                 'asset' => true,
                 'location' => 'vendor/daterangepicker/daterangepicker.js'
             ],
             [
                 'type' => 'css',
                 'asset' => true,
                 'location' => 'vendor/daterangepicker/daterangepicker.css'
             ],
          ],
      ],
      'Colorpicker' => [
         'name' => 'Colorpicker',
         'active' => true,
         'files' => [
             [
                 'type' => 'js',
                 'asset' => true,
                 'location' => 'vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js'
             ],
             [
                 'type' => 'css',
                 'asset' => true,
                 'location' => 'vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css'
             ],
          ],
      ],
      'FullCalendar' => [
         'name' => 'FullCalendar',
         'active' => true,
         'files' => [
             [
                 'type' => 'js',
                 'asset' => true,
                 'location' => 'vendor/fullcalendar/main.min.js'
             ],
             [
                 'type' => 'css',
                 'asset' => true,
                 'location' => 'vendor/fullcalendar/main.min.css'
             ],
             [
                 'type' => 'js',
                 'asset' => true,
                 'location' => 'vendor/fullcalendar/locales-all.min.js'
             ],
             [
                 'type' => 'js',
                 'asset' => true,
                 'location' => 'vendor/moment/moment.min.js'
             ],
          ],
      ],
      'Datatables' => [
          'name' => 'Datatables',
          'active' => true,
          'files' => [
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables/js/jquery.dataTables.js'
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables/js/dataTables.bootstrap4.min.js'
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables-plugins/responsive/js/dataTables.responsive.min.js'
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables-plugins/responsive/js/responsive.bootstrap4.min.js'
              ],
              [
                  'type' => 'css',
                  'asset' => true,
                  'location' => 'vendor/datatables/css/dataTables.bootstrap4.css'
              ],
              [
                  'type' => 'css',
                  'asset' => true,
                  'location' => 'vendor/datatables-plugins/responsive/css/responsive.bootstrap4.css'
              ],
          ],
      ],
      'DatatableButtons' => [
          'name' => 'DatatableButtons',
          'active' => true,
          'files' => [
              [
                  'type' => 'css',
                  'asset' => true,
                  'location' => 'vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables-plugins/jszip/jszip.min.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables-plugins/buttons/js/buttons.bootstrap4.min.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables-plugins/buttons/js/buttons.html5.min.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables-plugins/buttons/js/buttons.flash.min.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables-plugins/buttons/js/buttons.print.min.js',
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
                  // 'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                  'location' => 'vendor/select2/js/select2.min.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  // 'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                  'location' => 'vendor/select2/js/i18n/de.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  // 'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                  'location' => 'vendor/select2/js/i18n/en.js',
              ],
              [
                  'type' => 'css',
                  'asset' => true,
                  // 'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                  'location' => 'vendor/select2/css/select2.css',
              ],
              [
                  'type' => 'css',
                  'asset' => true,
                  //'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                  'location' => 'vendor/select2-bootstrap4-theme/select2-bootstrap4.css',
              ],
          ],
      ],
      'Chartjs' => [
          'name' => 'Chartjs',
          'active' => true,
          'files' => [
              [
                  'type' => 'css',
                  'asset' => true,
                  'location' => 'vendor/chart.js/Chart.css',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/moment/moment.min.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/chart.js/Chart.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/chart.js-plugins/chartjs-plugin-colorschemes.min.js',
              ],
          ],
      ],
      'ICheck' => [
          'name' => 'ICheck',
          'active' => true,
          'files' => [
              [
                  'type' => 'css',
                  'asset' => true,
                  'location' => 'vendor/icheck-bootstrap/icheck-bootstrap.min.css',
              ],
          ],
      ],
      'Toastr' => [
          'name' => 'Toastr',
          'active' => true,
          'files' => [
              [
                  'type' => 'css',
                  'asset' => true,
                  'location' => 'vendor/toastr/toastr.min.css',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/toastr/toastr.min.js',
              ],
          ],
      ],
      'Pace' => [
          'name' => 'Pace',
          'active' => true,
          'files' => [
              [
                  'type' => 'css',
                  'asset' => true,
                  'location' => 'vendor/pace-progress/themes/blue/pace-theme-center-radar.css',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/pace-progress/pace.min.js',
              ],
          ],
      ],
  ],
];
