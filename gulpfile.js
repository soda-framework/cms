var elixir = require('laravel-elixir');
elixir.config.assetsPath = 'resources';
elixir.config.appPath = 'src';
elixir.config.viewPath = 'views';
elixir.config.js.folder = '/';
elixir.config.css.sass.folder = '/';

elixir(function(mix) {

    // SASS FILE COMPILATION
    // Main scss file
    mix.sass(['scss/application.scss'], 'public/css/application.css');

    // For tree
    mix.sass(['scss/tree.scss'], 'public/css/tree.css');

    // Move assets to public location.
    mix.copy('components', 'public/components');
    mix.copy('img', 'public/img');
    mix.copy('fonts', 'public/fonts');

    // JS FILE CONCAT
    // Main js on all (or most) pages
    mix.scripts([
        'components/jquery/dist/jquery.js',
        'components/bootstrap/js/dist/util.js',
        'components/bootstrap/js/dist/button.js',
        'components/bootstrap/js/dist/tab.js',
        'components/bootstrap/js/dist/dropdown.js',
        'components/bootstrap/js/dist/modal.js',
        'components/bootstrap/js/dist/collapse.js',
        'components/bootstrap-fileinput/js/fileinput.js',
        'components/jquery-sortable/source/js/jquery-sortable.js', //TODO: move this to a file on it's own?
    ], 'public/js/application.js')

    // For content page(s)
    // TODO: REMOVE... jstree is no longer used.
    .scripts(['components/jstree/dist/jstree.js'], 'public/js/content.js')

    // For page editing scripts
    .scripts(['components/tinymce/tinymce.js'], 'public/js/pageedit.js');

    // TODO: I don't think this is actually used
    mix.version([
        'soda/soda/css',
        'soda/soda/js'
    ]);
});
