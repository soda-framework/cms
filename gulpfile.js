var elixir = require('laravel-elixir');
elixir.config.assetsPath = 'public';
elixir.config.appPath = 'src';
elixir.config.viewPath = 'views';
elixir.config.js.folder = '/';
elixir.config.css.sass.folder = '/';

var gulp = require('gulp');
var shell = require('gulp-shell');

var main_public_folder = '../../../public/sodacms/sodacms';

/**
 * Test task
 **/
//gulp.task("speak", function() {
//    var message = "copied.";
//    gulp.src("").pipe(shell("say " + message));
//});

elixir(function(mix) {

//    mix.task('speak', 'resources/**/*.*');

    // SASS FILE COMPILATION
    // Main scss file
    mix.sass(['sass/application.scss'], 'public/css/application.css')
        .copy('public/css', main_public_folder+'/css');

    // For tree scss
    mix.sass(['sass/tree.scss'], 'public/css/tree.css')
        .copy('public/css', main_public_folder+'/css');


    // JS FILE CONCAT
    // Main js on all (or most) pages
    mix.scripts([
        'components/jquery/dist/jquery.js',
        //'components/bootstrap-sass/assets/javascripts/bootstrap/util.js',
        'components/bootstrap-sass/assets/javascripts/bootstrap/button.js',
        'components/bootstrap-sass/assets/javascripts/bootstrap/tab.js',
        'components/bootstrap-sass/assets/javascripts/bootstrap/dropdown.js',
        'components/bootstrap-sass/assets/javascripts/bootstrap/modal.js',
        'components/bootstrap-sass/assets/javascripts/bootstrap/collapse.js',
        'components/bootstrap-sass/assets/javascripts/bootstrap/transition.js',

        'components/bootstrap-fileinput/js/fileinput.js',       //TODO: move this to a file on it's own?
        'components/bootstrap-fileinput/themes/fa/fa.js',       //TODO: move this to a file on it's own?
        'components/jquery-sortable/source/js/jquery-sortable.js', //TODO: move this to a file on it's own?

        'components/tinymce/tinymce.min.js', //TODO: move this to a file on it's own?

        'components/moment/min/moment.min.js',      //TODO: for datetimepicker only - move this somewhere else?
        'components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
    ], 'public/js/application.js')

    // For content page(s)
    // TODO: REMOVE... jstree is no longer used.
   // .scripts(['components/jstree/dist/jstree.js'], 'public/js/content.js')

    // For page editing scripts
    .scripts(['components/tinymce/tinymce.js'], 'public/js/pageedit.js').copy('resources/components','public/components').copy('public', main_public_folder);
});
