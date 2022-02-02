window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    // window.Popper = require('@popperjs/core').default;
    window.$ = window.jQuery = require('jquery');

    // basisc
    require('bootstrap');
    require('overlayscrollbars');
    window.toastr = require('toastr');
    window.toastr.options.closeButton = true;
    window.toastr.options.closeMethod = 'fadeOut';
    window.toastr.options.closeDuration = 60;
    window.toastr.options.closeEasing = 'swing';
    window.toastr.options.progressBar = true;


} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
window.io = require('socket.io-client');
// window.Pusher = require('pusher-js');

/* window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: false,
    wsHost: window.location.hostname,
    wsPort: 80,
    wssPort: 443,
    disableStats: true,
}); */

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname  + ':6001', // this is laravel-echo-server host
});
