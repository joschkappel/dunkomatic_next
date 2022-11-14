const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps()
    .combine([
        'node_modules/fullcalendar/main.min.css',
        // 'node_modules/datatables.net-searchpanes-bs4/css/searchPanes.bootstrap4.min.css',
        // 'node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css',
         ], 'public/css/vendor.css')
    .combine([
        'node_modules/jszip/dist/jszip.min.js',
        'node_modules/fullcalendar/main.min.js',
        'node_modules/fullcalendar/locales-all.min.js',
        'node_modules/summernote/dist/summernote-bs4.min.js',
        'node_modules/summernote/dist/lang/summernote-de-DE.min.js',
/*         'node_modules/datatables.net-searchpanes/js/dataTables.searchPanes.min.js',
        'node_modules/datatables.net-select/js/dataTables.select.min.js',
        'node_modules/datatables.net-searchpanes-bs4/js/searchPanes.bootstrap4.min.js',
        'node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js', */
        ], 'public/js/vendor.js')
    .copy('resources/vendor/datatables.net/lang/de.json','public/lang/vendor/datatables.net/de.json')
    .copy('resources/vendor/datatables.net/lang/en.json','public/lang/vendor/datatables.net/en.json');
