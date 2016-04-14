var elixir = require('laravel-elixir');
elixir.config.assetsPath = 'soda/soda/src/Assets';

elixir(function(mix) {

    //SASS FILE COMPILATION
    //main scss file
    mix.sass([
        'application.scss',
    ], 'public/soda/soda/css/application.css');

    //for tree
    mix.sass([
        'tree.scss',
    ], 'public/soda/soda/css/tree.css');

    //move assets to public location.
    mix.copy('soda/soda/src/Assets/Components', 'public/soda/soda/components');

    //JS FILE CONCAT
    //main js on all (or most) pages
    mix.scripts([
            '../Components/jquery/dist/jquery.js',
            '../Components/bootstrap/js/dist/util.js',
            '../Components/bootstrap/js/dist/button.js',
            '../Components/bootstrap/js/dist/tab.js',
            '../Components/bootstrap/js/dist/dropdown.js',
            '../Components/bootstrap-fileinput/js/fileinput.js',

            //TODO: move this to a file on it's own?
            '../Components/jquery-sortable/source/js/jquery-sortable.js'
        ],
        'public/soda/soda/js/application.js')

    //for content page(s) //TODO: REMOVE... jstree is no longer used.
    .scripts([
            '../Components/jstree/dist/jstree.js'
        ],
        'public/soda/soda/js/content.js')

    //for page editing scripts
    .scripts([
            '../Components/tinymce/tinymce.js'
        ],
        'public/soda/soda/js/pageedit.js');

    mix.version([
        'soda/soda/css',
        'soda/soda/js'
        ]);

});