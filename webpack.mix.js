const {mix} = require('laravel-mix');
const CleanWebpackPlugin = require('clean-webpack-plugin');

var pathsToClean = [
    'public/assets/js',
    'public/assets/css'
];

// the clean options to use
var cleanOptions = {};

mix.webpackConfig({
    plugins: [
        new CleanWebpackPlugin(pathsToClean, cleanOptions)
    ]
});

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
    'bower_components/PACE/pace.js',
    'bower_components/bootstrap/dist/js/bootstrap.js',
    'bower_components/gentelella/build/js/custom.js',
    'resources/assets/js/default.js'
], 'public/assets/js/default.js').version();

mix.styles([
    'bower_components/font-awesome/css/font-awesome.css',
    'bower_components/PACE/themes/blue/pace-theme-minimal.css',
    'bower_components/bootstrap/dist/css/bootstrap.css',
    'bower_components/gentelella/vendors/animate.css/animate.css',
    'bower_components/gentelella/build/css/custom.css',
    'resources/assets/css/default.css'
], 'public/assets/css/default.css').version();

mix.copy([
    'bower_components/font-awesome/fonts/',
    'bower_components/gentelella/vendors/bootstrap/dist/fonts'
], 'public/assets/fonts');

mix.scripts([
    'bower_components/SyntaxHighlighter/scripts/XRegExp.js',
    'bower_components/SyntaxHighlighter/scripts/shCore.js',
    'bower_components/SyntaxHighlighter/scripts/shBrushXml.js',
    'bower_components/SyntaxHighlighter/scripts/shBrushPhp.js',
    'bower_components/FooTable/compiled/footable.min.js',
    'resources/assets/js/try_and_buy.js'
], 'public/assets/js/try_and_buy.js').version();

mix.styles([
    'bower_components/SyntaxHighlighter/styles/shCore.css',
    'bower_components/SyntaxHighlighter/styles/shThemeDefault.css',
    'bower_components/FooTable/compiled/footable.bootstrap.min.css'
], 'public/assets/css/try_and_buy.css').version();

mix.scripts([
    'bower_components/SyntaxHighlighter/scripts/XRegExp.js',
    'bower_components/SyntaxHighlighter/scripts/shCore.js',
    'bower_components/SyntaxHighlighter/scripts/shBrushXml.js',
    'bower_components/SyntaxHighlighter/scripts/shBrushPhp.js',
    'bower_components/FooTable/compiled/footable.min.js',
    'resources/assets/js/subscription.js'
], 'public/assets/js/subscription.js').version();

mix.styles([
    'bower_components/SyntaxHighlighter/styles/shCore.css',
    'bower_components/SyntaxHighlighter/styles/shThemeDefault.css',
    'bower_components/FooTable/compiled/footable.bootstrap.min.css'
], 'public/assets/css/subscription.css').version();