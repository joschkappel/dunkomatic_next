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
    .combine([
        'node_modules/fullcalendar/main.min.css',
        'node_modules/pace-progress/themes/blue/pace-theme-center-radar.css',
        'resources/css/adminlte.css'], 'public/css/vendor.css')
    .combine([
        'node_modules/fullcalendar/main.min.js',
        'node_modules/fullcalendar/locales-all.min.js',
        'node_modules/pace-progress/pace.js',
        'node_modules/summernote/dist/summernote-bs4.min.js',
        'node_modules/summernote/dist/lang/summernote-de-DE.min.js',
        'resources/js/adminlte.js'], 'public/js/vendor.js')
    .copy('resources/vendor/datatables.net/lang/de.json','public/lang/vendor/datatables.net/de.json')
    .copy('resources/vendor/datatables.net/lang/en.json','public/lang/vendor/datatables.net/en.json');
