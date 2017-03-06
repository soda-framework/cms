const elixir = require('laravel-elixir');


elixir.config.notifications = false;
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
        .sass('extra.scss');

    mix.scripts('application.js')
        .scripts('page-tree.js')
        .scripts('forms/slugs.js', 'public/js/forms/slugs.js')
        .scripts([
            './resources/components/jquery/dist/jquery.js',
            './resources/components/jquery-ui/jquery-ui.js',
            './resources/components/bootstrap/js/collapse.js',
            './resources/components/bootstrap/js/button.js',
            './resources/components/bootstrap/js/tab.js',
            './resources/components/bootstrap/js/dropdown.js',
            './resources/components/bootstrap/js/modal.js',
            './resources/components/bootstrap/js/collapse.js',
            './resources/components/bootstrap/js/transition.js',
            './resources/components/bootstrap/js/tooltip.js',
            './resources/components/JVFloat/jvfloat.js',
        ], 'public/js/core.js')
        .scripts([
            './resources/components/nested-sortable/jquery.nested-sortable.js',
        ], 'public/js/forms/sortable.js')
        .scripts([
            './resources/components/moment/min/moment.min.js',
            './resources/components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
        ], 'public/js/forms/dates.js')
        .scripts([
            './resources/components/tinymce/tinymce.min.js',
            './resources/components/tinymce/jquery.tinymce.min.js',
        ], 'public/js/forms/tinymce.js')
        .scripts([
            './resources/components/bootstrap-fileinput/js/plugins/sortable.js',
            './resources/components/bootstrap-fileinput/js/fileinput.js',
            './resources/components/bootstrap-fileinput/themes/fa/theme.js',
        ], 'public/js/forms/upload.js')
        .scripts([
            './resources/components/select2/dist/js/select2.full.min.js',
        ], 'public/js/forms/multiselect.js')
        .scripts([
            './resources/components/jsoneditor/dist/jsoneditor-minimalist.js',
        ], 'public/js/forms/json.js');

    mix.copy('resources/components/bootstrap/fonts', 'public/fonts/bootstrap')
        .copy('resources/components/font-awesome/fonts', 'public/fonts/font-awesome')
        .copy('resources/components/bootstrap-fileinput/img', 'public/components/bootstrap-fileinput/img')
        .copy('resources/components/jsoneditor/dist/img', 'public/components/jsoneditor/img')
        .copy('resources/components/tinymce/plugins', 'public/components/tinymce/plugins')
        .copy('resources/components/tinymce/skins', 'public/components/tinymce/skins');
});
