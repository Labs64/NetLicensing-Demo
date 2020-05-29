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

mix.scripts([
    'node_modules/jquery/dist/jquery.js',
    'node_modules/pace-progress/pace.js',
    'node_modules/bootstrap/dist/js/bootstrap.js',
    'node_modules/gentelella/build/js/custom.js',
    'resources/assets/js/default.js'
], 'public/assets/js/default.js').version();

mix.styles([
    'node_modules/font-awesome/css/font-awesome.css',
    'node_modules/pace-progress/themes/blue/pace-theme-minimal.css',
    'node_modules/bootstrap/dist/css/bootstrap.css',
    'node_modules/gentelella/vendors/animate.css/animate.css',
    'node_modules/gentelella/build/css/custom.css',
    'resources/assets/css/default.css'
], 'public/assets/css/default.css').version();

mix.copy([
    'node_modules/font-awesome/fonts/',
    'node_modules/gentelella/vendors/bootstrap/dist/fonts'
], 'public/assets/fonts');

mix.scripts([
    'node_modules/syntaxhighlighter/dist/syntaxhighlighter.js',
    'node_modules/syntaxhighlighter/src/js/shBrushXml.js',
    'node_modules/footable/compiled/footable.min.js',
    'node_modules/bootstrap-toggle/js/bootstrap2-toggle.min.js',
    'resources/assets/js/try_and_buy.js'
], 'public/assets/js/try_and_buy.js').version();

mix.styles([
    'node_modules/footable/compiled/footable.bootstrap.min.css',
    'node_modules/syntaxhighlighter/dist/theme.css',
    'node_modules/bootstrap-toggle/css/bootstrap2-toggle.min.css'
], 'public/assets/css/try_and_buy.css').version();

mix.scripts([
    'node_modules/syntaxhighlighter/dist/syntaxhighlighter.js',
    'node_modules/syntaxhighlighter/src/js/shBrushXml.js',
    'node_modules/footable/compiled/footable.min.js',
    'node_modules/bootstrap-toggle/js/bootstrap2-toggle.min.js',
    'resources/assets/js/subscription.js'
], 'public/assets/js/subscription.js').version();

mix.styles([
    'node_modules/footable/compiled/footable.bootstrap.min.css',
    'node_modules/syntaxhighlighter/dist/theme.css',
    'node_modules/bootstrap-toggle/css/bootstrap2-toggle.min.css'
], 'public/assets/css/subscription.css').version();
