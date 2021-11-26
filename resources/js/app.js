/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


try {
    global.moment = require('moment');
    require('moment-timezone');
    require('tempusdominus-bootstrap');

    require('bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js');
    require('bootstrap-fileinput');
    require('bootstrap-fileinput/js/locales/de.js');
    require('bootstrap4-duallistbox');

    //selectbox
    require('select2');
    require('select2/dist/js/i18n/de.js');
    jQuery(function () {
        $(".select2").select2({ theme: 'bootstrap4', focus: true });
     })

    // bootstrap datatables...
    require( 'jszip' );
    require( 'datatables.net-bs4' );
    require( 'datatables.net-buttons-bs4' );
    require( 'datatables.net-buttons/js/buttons.colVis.js' );
    require( 'datatables.net-buttons/js/buttons.flash.js' );
    require( 'datatables.net-buttons/js/buttons.html5.js' );
    require( 'datatables.net-buttons/js/buttons.print.js' );
    require( 'datatables.net-colreorder-bs4' );
    require( 'datatables.net-fixedcolumns-bs4' );
    require( 'datatables.net-fixedheader-bs4' );
    require( 'datatables.net-responsive-bs4' );
    require( 'datatables.net-rowreorder-bs4' );
    require( 'datatables.net-scroller-bs4' );
    require( 'datatables.net-select-bs4' );
    // bs4 no js - require direct component
    // styling only packages for bs4
    require( 'datatables.net-keytable' );
    require( 'datatables.net-rowgroup' );
    // pdfMake
    var pdfMake = require('pdfmake/build/pdfmake.js');
    var pdfFonts = require('pdfmake/build/vfs_fonts.js');
    pdfMake.vfs = pdfFonts.pdfMake.vfs;

    // charts
    require('chart.js');
    require('chartjs-plugin-colorschemes');

    // others
    require('toastr');
    require('ion-rangeslider');

} catch (e) {
    console.log(e);
}
