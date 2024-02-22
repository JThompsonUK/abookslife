const mix = require('laravel-mix');
const path = require('path');

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

mix.webpackConfig({
    resolve: {
        alias: {
            ziggy: path.resolve('vendor/tightenco/ziggy/dist'),
        }
    }
});
mix.js('resources/js/scripts.js', 'public/js')
mix.js('resources/js/app.js', 'public/js')
    .vue(3)
    .sass('resources/sass/app.scss', 'public/css')
    // .postCss('resources/css/app.css', 'public/css', [
    //     //
    // ])
    .version();
