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
 | https://github.com/JeffreyWay/laravel-mix/blob/628f6062cceb77610b1813e3179abcbd043a4642/UPGRADE.md#vue-configuration
 */

mix.js('resources/js/app.js', 'public/js').vue({
    extractStyles: true,
    globalStyles: false
}).sass('resources/sass/app.scss', 'public/css');
