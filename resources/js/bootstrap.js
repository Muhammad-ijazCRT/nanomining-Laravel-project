/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

console.log(process.env.MIX_PUSHER_APP_KEY);
console.log(process.env.MIX_PUSHER_APP_CLUSTER);

window.Echo = new Echo({
    // Options mentioned by pusher
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: process.env.MIX_PUSHER_SCHEME == "http",
    // Options exclusively by beyond code
    wsHost: '127.0.0.1',
    wsPort: 6001,
    disableStats: true,
    //
    // wssPort: import.meta.env.VITE_PUSHER_PORT,
    // enabledTransports: ['ws', 'wss'],
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                authenticatedPost("/api/broadcasting/auth", {
                    socket_id: socketId,
                    channel_name: channel.name,
                })
                    .then((response) => {
                        callback(false, response.data);
                        console.log(response.data);
                    })
                    .catch((error) => {
                        callback(true, error);
                        console.log(error);
                    });
            },
        };
    },
});


console.log(window.Echo);


// console.log(process.env.MIX_PUSHER_APP_KEY);
// console.log(process.env.MIX_PUSHER_APP_CLUSTER);


// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: 'mt1',
//     forceTLS: false,
//     wsHost: '127.0.0.1',
//     wsPort: 6001,
//     disableStats:true,

// });

// console.log(window.Echo);





