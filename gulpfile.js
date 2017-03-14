const elixir = require('laravel-elixir');


//elixir.config.notifications = false;
elixir.config.appPath = 'src';
elixir.config.css.sass.folder = 'scss';
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
    mix.sass('application.scss')
        .sass('bootstrap.scss')
        .sass('fonts.scss')
        .sass('plugins.scss');

    mix.scripts('application.js')
        .scripts('page-tree.js')
        .scripts('forms/slugs.js', 'public/js/forms/slugs.js')
        .scripts([
            './node_modules/jquery/dist/jquery.js',
            './node_modules/jquery-ui-dist/jquery-ui.js',
            './node_modules/bootstrap/js/button.js',
            './node_modules/bootstrap/js/collapse.js',
            './node_modules/bootstrap/js/dropdown.js',
            './node_modules/bootstrap/js/modal.js',
            './node_modules/bootstrap/js/tab.js',
            './node_modules/bootstrap/js/tooltip.js',
            './node_modules/bootstrap/js/transition.js',
            './node_modules/JVFloat/jvfloat.js',
        ], 'public/js/core.js')
        .scripts([
            './node_modules/nestedSortable/jquery.mjs.nestedSortable.js',
        ], 'public/js/forms/sortable.js')
        .scripts([
            './node_modules/moment/min/moment.min.js',
            './node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
        ], 'public/js/forms/dates.js')
        .scripts([
            './node_modules/tinymce/tinymce.min.js',
            './node_modules/tinymce/jquery.tinymce.min.js',
        ], 'public/js/forms/tinymce.js')
        .scripts([
            './node_modules/bootstrap-fileinput/js/plugins/sortable.js',
            './node_modules/bootstrap-fileinput/js/fileinput.js',
            './node_modules/bootstrap-fileinput/themes/fa/theme.js',
        ], 'public/js/forms/upload.js')
        .scripts([
            './node_modules/select2/dist/js/select2.full.min.js',
        ], 'public/js/forms/multiselect.js')
        .scripts([
            './node_modules/jsoneditor/dist/jsoneditor-minimalist.js',
        ], 'public/js/forms/json.js');

    mix.copy('node_modules/bootstrap/fonts', 'public/fonts/bootstrap')
        .copy('node_modules/font-awesome/fonts', 'public/fonts/font-awesome')
        .copy('node_modules/mdi/fonts', 'public/fonts/mdi')
        .copy('node_modules/bootstrap-fileinput/img', 'public/components/bootstrap-fileinput/img')
        .copy('node_modules/jsoneditor/dist/img', 'public/components/jsoneditor/img')
        .copy('node_modules/tinymce/plugins', 'public/components/tinymce/plugins')
        .copy('node_modules/tinymce/skins', 'public/components/tinymce/skins')
        .copy('resources/assets/img', 'public/img');
});
