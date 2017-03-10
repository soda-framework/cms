const elixir = require('laravel-elixir');

elixir.config.notifications = false;
elixir.config.appPath = 'src';
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');

    mix.scripts('app.js')
        .scripts([
            // Remember to remove what you don't need!
            './node_modules/jquery/dist/jquery.js',
            './node_modules/bootstrap-sass/assets/javascripts/bootstrap/affix.js',
            './node_modules/bootstrap-sass/assets/javascripts/bootstrap/alert.js',
            './node_modules/bootstrap-sass/assets/javascripts/bootstrap/button.js',
            './node_modules/bootstrap-sass/assets/javascripts/bootstrap/carousel.js',
            './node_modules/bootstrap-sass/assets/javascripts/bootstrap/collapse.js',
            './node_modules/bootstrap-sass/assets/javascripts/bootstrap/dropdown.js',
            './node_modules/bootstrap-sass/assets/javascripts/bootstrap/modal.js',
            './node_modules/bootstrap-sass/assets/javascripts/bootstrap/popover.js',
            './node_modules/bootstrap-sass/assets/javascripts/bootstrap/scrollspy.js',
            './node_modules/bootstrap-sass/assets/javascripts/bootstrap/tab.js',
            './node_modules/bootstrap-sass/assets/javascripts/bootstrap/tooltip.js',
            './node_modules/bootstrap-sass/assets/javascripts/bootstrap/transition.js',
        ], 'public/js/vendor.js')

    mix.copy('node_modules/bootstrap-sass/assets/fonts', 'public/fonts');
});
