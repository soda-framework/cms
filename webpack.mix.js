const { mix } = require('laravel-mix');

mix.sass('./resources/assets/scss/application.scss', 'public/css')
    .sass('./resources/assets/scss/bootstrap.scss', 'public/css')
    .sass('./resources/assets/scss/fonts.scss', 'public/css')
    .sass('./resources/assets/scss/plugins.scss', 'public/css')
    .sass('./resources/assets/scss/themes/default.scss', 'public/css/themes')
    .sass('./resources/assets/scss/themes/lime.scss', 'public/css/themes')
    .sass('./resources/assets/scss/themes/strawberry.scss', 'public/css/themes')
    .sass('./resources/assets/scss/themes/grape.scss', 'public/css/themes')
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
    ], 'public/js/core.js')
    .scripts([
        './node_modules/nestedSortable/jquery.mjs.nestedSortable.js'
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
        './node_modules/jsoneditor/dist/jsoneditor-minimalist.js'
    ], 'public/js/forms/json.js')
    .copy('node_modules/bootstrap/fonts', 'public/fonts/bootstrap')
    .copy('node_modules/font-awesome/fonts', 'public/fonts/font-awesome')
    .copy('node_modules/mdi/fonts', 'public/fonts/mdi')
    .copy('node_modules/bootstrap-fileinput/img', 'public/components/bootstrap-fileinput/img')
    .copy('node_modules/jsoneditor/dist/img', 'public/components/jsoneditor/img')
    .copy('node_modules/tinymce/plugins', 'public/components/tinymce/plugins')
    .copy('node_modules/tinymce/skins', 'public/components/tinymce/skins')
    .copy('node_modules/tinymce/themes', 'public/components/tinymce/themes')
    .copy('resources/assets/img', 'public/img')
    .copy('resources/assets/js', 'public/js');
