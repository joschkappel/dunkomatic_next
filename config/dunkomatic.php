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
  'league_team_chars' => array( 1 => 'A','B','C','D','E','F','G','H','I','K','L','M','N','O','P','Q'),
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
      'Duallistbox' => [
        'name' => 'Duallistbox',
        'active' => true,
        'files' => [
          [
            'type' => 'js',
            'asset' => true,
            'location' => 'vendor/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js'
          ],
          [
            'type' => 'css',
            'asset' => true,
            'location' => 'vendor/bootstrap4-duallistbox/bootstrap-duallistbox.min.css'
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
                  'location' => 'vendor/datatables.net/datatables.net/js/jquery.dataTables.min.js'
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables.net/datatables.net-bs4/js/dataTables.bootstrap4.min.js'
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables.net/datatables.net-responsive/js/dataTables.responsive.min.js'
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables.net/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js'
              ],
              [
                  'type' => 'css',
                  'asset' => true,
                  'location' => 'vendor/datatables.net/datatables.net-bs4/css/dataTables.bootstrap4.min.css'
              ],
              [
                  'type' => 'css',
                  'asset' => true,
                  'location' => 'vendor/datatables.net/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css'
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
                  'location' => 'vendor/datatables.net/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables.net/datatables.net-buttons/js/dataTables.buttons.min.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables.net/jszip/jszip.min.js',
              ],
              [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/datatables.net/pdfmake/pdfmake.min.js',
              ],
/*               [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/datatables.net/pdfmake/pdfmake.min.js.map',
              ],
 */              [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/datatables.net/pdfmake/vfs_fonts.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables.net/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables.net/datatables.net-buttons/js/buttons.html5.min.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables.net/datatables.net-buttons/js/buttons.flash.min.js',
              ],
              [
                  'type' => 'js',
                  'asset' => true,
                  'location' => 'vendor/datatables.net/datatables.net-buttons/js/buttons.print.min.js',
              ],
              [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/datatables.net/datatables.net-buttons/js/buttons.colVis.min.js',
            ],
          ],
      ],
      'DatatableSelect' => [
        'name' => 'DatatableSelect',
        'active' => true,
        'files' => [
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'vendor/datatables.net/datatables.net-select-bs4/css/select.bootstrap4.min.css',
            ],
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/datatables.net/datatables.net-select/js/dataTables.select.min.js',
            ],
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/datatables.net/datatables.net-select-bs4/js/select.bootstrap4.min.js',
            ],
        ],
    ],
    'DatatableRowgroup' => [
        'name' => 'DatatableRowgroup',
        'active' => true,
        'files' => [
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'vendor/datatables.net/datatables.net-rowgroup-bs4/css/rowGroup.bootstrap4.min.css',
            ],
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/datatables.net/datatables.net-rowgroup/js/dataTables.rowGroup.min.js',
            ],
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/datatables.net/datatables.net-rowgroup-bs4/js/rowGroup.bootstrap4.min.js',
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
      'FileUpload' => [
        'name' => 'FileUpload',
        'active' => true,
        'files' => [
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/kartik-v/bootstrap-fileinput/js/fileinput.min.js',
            ],
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'vendor/kartik-v/bootstrap-fileinput/css/fileinput.min.css',
            ],
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/kartik-v/bootstrap-fileinput/themes/fas/theme.min.js',
            ],
        ],
    ],
  ],
];
