let mix = require('laravel-mix');

const themepath = 'themes/demo';
const distpath = themepath + '/dist/';

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
    'node_modules/jquery-legacy/node_modules/jquery/dist/jquery.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.min.js',
    'node_modules/owl.carousel/dist/owl.carousel.min.js',
    'node_modules/wow.js/dist/wow.min.js',
    'node_modules/jquery-parallax/scripts/jquery.parallax-1.1.3.js',
    'node_modules/jquery-countdown/dist/jquery.countdown.min.js',
    'node_modules/flexslider/jquery.flexslider.js',
    'node_modules/chosen-jquery/lib/chosen.jquery.min.js',
    'node_modules/jquery.counterup/jquery.counterup.min.js',
    'node_modules/waypoints/src/waypoint.js',
    'node_modules/sweetalert/dist/sweetalert.min.js',
    'node_modules/jquery-bar-rating/jquery.barrating.js',
    'node_modules/bootstrap-notify/bootstrap-notify.min.js',
    'resources/assets/user/js/book_detail.js',
    ], 'public/assets/user/js/app.js');
mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.min.css',
    'node_modules/owl.carousel/dist/assets/owl.carousel.css',
    'node_modules/font-awesome/css/font-awesome.min.css',
    'node_modules/flexslider/flexslider.css',
    'node_modules/chosen-jquery/lib/chosen.min.css',
    'node_modules/animate.css/animate.min.css',
    'node_modules/jquery-bar-rating/dist/themes/fontawesome-stars-o.css',
    'resources/assets/css/app.css',
], 'public/assets/user/css/app.css');
mix.copyDirectory('resources/assets/user', 'public/assets/user').browserSync('http://127.0.0.1:8000/');
mix.copyDirectory('resources/assets/fonts', 'public/assets/user/fonts');
mix.copy('node_modules/bootstrap-notify/bootstrap-notify.min.js', 'public/assets/admin/js/bootstrap-notify.min.js');
mix.copy('resources/assets/js/notify.js', 'public/assets/js/notify.js');
mix.copyDirectory('resources/assets/admin', 'public/assets/admin');
mix.copyDirectory('resources/assets/img', 'public/assets/img');
mix.copyDirectory('resources/assets/img/user', 'public/storage/img/user');
mix.copy('resources/assets/js/config.js', 'public/assets/js/config.js');
mix.copy('resources/assets/user/js/book.js', 'public/assets/user/js/book.js');
mix.copy('resources/assets/user/js/vote.js', 'public/assets/user/js/vote.js');

mix.copy('resources/assets/js/c3/c3.min.js', 'public/assets/js/c3/c3.min.js');
mix.copy('resources/assets/js/d3/d3.min.js', 'public/assets/js/d3/d3.min.js');
mix.copy('resources/assets/css/c3/c3.min.css', 'public/assets/css/c3/c3.min.css');
mix.copy('resources/assets/user/css/search-page.css', 'public/assets/css/search-page.css');
//admin
mix.autoload({
    jquery: ['$', 'jQuery', 'window.jQuery'],
});
mix.styles([
    'public/bower_components/admin-assets/css/icons/icomoon/styles.css',
    'public/bower_components/admin-assets/css/bootstrap.css',
    'public/bower_components/admin-assets/css/core.css',
    'public/bower_components/admin-assets/css/components.css',
    'public/bower_components/admin-assets/css/colors.css',
    'public/bower_components/admin-assets/css/datatables/jquery.dataTables.min.css',
    'resources/assets/css/app.css',
    'resources/assets/admin/css/app.css',
], 'public/assets/admin/css/app.css')
.js([
    'public/bower_components/admin-assets/js/core/libraries/bootstrap.min.js',
    'public/bower_components/admin-assets/js/plugins/loaders/blockui.min.js',
    'public/bower_components/admin-assets/js/plugins/forms/styling/uniform.min.js',
    'public/bower_components/admin-assets/js/core/app.js',
    'public/bower_components/admin-assets/js/pages/components_modals.js',
], 'public/assets/admin/js/app.js')
.options({
    processCssUrls: false
});
mix.copyDirectory('public/bower_components/admin-assets/css/icons/icomoon/fonts', 'public/assets/admin/css/fonts');
