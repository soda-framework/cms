/*

###################
#                 #
#  ASSET OPTIONS  #
#                 #
###################

TYPE -------- OPTION -------- DEFAULT ------ DESCRIPTION
global ------ path ---------- undefined ---- pattern to match file in input folder
css --------- outputs ------- compressed --- output style (compressed, expanded, etc)
css --------- autoprefix ---- true --------- automatically adds vendor prefixes for unsupported css (e.g. -webkit, -moz)
css --------- precision ----- 8 -------------number precision - useful if compiling bootstrap with its line-height var set to 1095702915325423 decimal places
css --------- sourcemaps ---- true --------- publish sourcemap?
css --------- min_suffix ---- true --------- add .min suffix to minifed files?
css --------- show_size ----- true --------- show file size in console log (minified only)
js ---------- sourcemaps ---- true --------- publish sourcemap?
js ---------- min_suffix ---- true --------- add .min suffix to minifed files?
js ---------- show_size ----- true --------- show file size in console log (original + minified)
components -- sourcemaps ---- false -------- publish sourcemap?
components -- min_suffix ---- true --------- add .min suffix to minifed files?
components -- show_size ----- true --------- show file size in console log (original + minified)


###################
#                 #
# PUBLISH OPTIONS #
#                 #
###################

TYPE -------- OPTION -------- DEFAULT ------ DESCRIPTION
global ------ path ---------- undefined ---- pattern to publish files of type
css --------- clean --------- false -------- if true, path will be cleared before publish.
js ---------- clean --------- false -------- if true, path will be cleared before publish.
components -- clean --------- true --------- if true, path will be cleared before publish.

*/

module.exports = {

    //working directory for all sass files, bower components, etc
    assets: {
        folder:             'resources',
        svg: {
            template:       'resources/sass/font/_font.tpl',
            formats:        ['ttf', 'eot', 'woff', 'woff2', 'svg']
        }
    },

    //array of directories that will be published to
    publish: [
        {
            folder:         'public'
        },
        {
            folder:         '../../../public/sodacms/sodacms',
            css: {
                clean:      true,
            },
            js: {
                clean:      true,
            },
            img: {
                clean:      true,
            },
            components: {
                clean:      true,
            }
        }
    ],

    //list all bower components, separated into arrays of packages to be concatenated
    components: {
        scripts: [
            //'resources/components/modernizr.js',
            'public/components/jquery/dist/jquery.js',
            //'components/bootstrap-sass/assets/javascripts/bootstrap/util.js',
            'public/components/bootstrap-sass/assets/javascripts/bootstrap/button.js',
            'public/components/bootstrap-sass/assets/javascripts/bootstrap/tab.js',
            'public/components/bootstrap-sass/assets/javascripts/bootstrap/dropdown.js',
            'public/components/bootstrap-sass/assets/javascripts/bootstrap/modal.js',
            'public/components/bootstrap-sass/assets/javascripts/bootstrap/collapse.js',
            'public/components/bootstrap-sass/assets/javascripts/bootstrap/transition.js',

            'public/components/bootstrap-fileinput/js/fileinput.js',       //TODO: move this to a file on it's own?
            'public/components/bootstrap-fileinput/themes/fa/fa.js',       //TODO: move this to a file on it's own?
            'public/components/jquery-sortable/source/js/jquery-sortable.js', //TODO: move this to a file on it's own?
            'public/components/tinymce/tinymce.min.js', //TODO: move this to a file on it's own?
            'public/components/moment/min/moment.min.js',      //TODO: for datetimepicker only - move this somewhere else?
            'public/components/select2/dist/js/select2.full.min.js', //TODO: for select2 only - move this somewhere else?

            'public/components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
        ]
        //
        //app : [
        //    'resources/js/application.js'
        //]

        //cms: [
            //'resources/components/tinymce/tinymce.js',
            //'resources/components/jquery-ui/jquery-ui.js',
        //],
    }
};
