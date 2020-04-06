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

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/jsonAnaliser.js', 'public/js')
   .js('resources/js/webhookEvents.js', 'public/js')
   .js('resources/js/apiEndpoints.js', 'public/js')
   .js('resources/js/actions.js', 'public/js')
   .js('resources/js/apis.js', 'public/js')
   .js('resources/js/fields.js', 'public/js')
   .js('resources/js/products.js', 'public/js')
   .js('resources/js/users.js', 'public/js')
   .js('resources/js/userProfiles.js', 'public/js')
   .js('resources/js/resetPassword.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');
