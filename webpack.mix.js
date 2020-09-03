const mix = require('laravel-mix');

mix.sass('src/resources/assets/impersonate.scss', 'src/resources/assets/dist/impersonate.css');

mix.scripts([
    'node_modules/tail.select/js/tail.select.min.js',
    'src/resources/assets/impersonate.js'
], 'src/resources/assets/dist/impersonate.js');
