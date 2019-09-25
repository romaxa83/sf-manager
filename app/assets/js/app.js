require('../css/app.scss');

require('bootstrap');
require('@coreui/coreui');

const Centrifuge = require('centrifuge');
const toastr = require('toastr');
// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');
//ws://localhost:8084/connection/websocket
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');


document.addEventListener('DOMContentLoaded', function(){
    let url = document.querySelector('meta[name=centrifugo-url]').getAttribute('content');
    let user = document.querySelector('meta[name=centrifugo-user]').getAttribute('content');
    let token = document.querySelector('meta[name=centrifugo-token]').getAttribute('content');

    const centrifuge = new Centrifuge(url);
    centrifuge.setToken(token);
    centrifuge.subscribe('alerts#' + user, function (message) {
        toastr.info(message.data.message);
    });

    centrifuge.connect();
});
