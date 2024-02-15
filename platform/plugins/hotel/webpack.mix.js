let mix = require('laravel-mix');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'platform/plugins/' + directory;
const dist = 'public/vendor/core/plugins/' + directory;

mix
    .sass(source + '/resources/assets/sass/hotel.scss', dist + '/css')
    .copy(dist + '/css/hotel.css', source + '/public/css')

    .js(source + '/resources/assets/js/currencies.js', dist + '/js')
    .copy(dist + '/js/currencies.js', source + '/public/js')

    .sass(source + '/resources/assets/sass/currencies.scss', dist + '/css')
    .copy(dist + '/css/currencies.css', source + '/public/css')

    .js(source + '/resources/assets/js/room-availability.js', dist + '/js')
    .copy(dist + '/js/room-availability.js', source + '/public/js');
