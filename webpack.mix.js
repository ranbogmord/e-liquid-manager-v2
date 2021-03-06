let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
  .js('resources/assets/js/ui.js', 'public/js')
  .js('resources/assets/js/admin.js', 'public/js')
  .sass('resources/assets/sass/login.scss', 'public/css')
  .sass('resources/assets/sass/app.scss', 'public/css')
  .sass('resources/assets/sass/admin.scss', 'public/css')
  .sass('resources/assets/sass/profile.scss', 'public/css')
  .copy('resources/assets/img/loader.svg', 'public/img')
  .copy('resources/assets/img/login.jpg', 'public/img')
  .sass('resources/assets/sass/base.scss', 'public/css')
