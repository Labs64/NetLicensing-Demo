const {mix} = require('laravel-mix');
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

/*
 |--------------------------------------------------------------------------
 | Core
 |--------------------------------------------------------------------------
 |
 */

mix.scripts([
    'node_modules/jquery/dist/jquery.js',
    'bower_components/PACE/pace.js',
    'bower_components/bootstrap/dist/js/bootstrap.js',
    'bower_components/gentelella/build/js/custom.js'

], 'public/assets/app/js/app.js').version();

mix.styles([
    'bower_components/font-awesome/css/font-awesome.css',
    'bower_components/PACE/themes/blue/pace-theme-minimal.css',
    'bower_components/bootstrap/dist/css/bootstrap.css',
    'bower_components/gentelella/vendors/animate.css/animate.css',
    'bower_components/gentelella/build/css/custom.css',
    'resources/assets/css/app.css'
], 'public/assets/app/css/app.css').version();

mix.copy([
    'bower_components/font-awesome/fonts/',
    'bower_components/gentelella/vendors/bootstrap/dist/fonts'
], 'public/assets/app/fonts');