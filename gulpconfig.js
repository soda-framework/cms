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
        folder:             'resources/assets',
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
            folder:         '../test/public/soda/cms',
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
        core: [
            //'resources/components/modernizr.js',
            'resources/components/jquery/dist/jquery.js',
            //'components/bootstrap-sass/assets/javascripts/bootstrap/util.js',
            'resources/components/bootstrap-sass/assets/javascripts/bootstrap/button.js',
            'resources/components/bootstrap-sass/assets/javascripts/bootstrap/tab.js',
            'resources/components/bootstrap-sass/assets/javascripts/bootstrap/dropdown.js',
            'resources/components/bootstrap-sass/assets/javascripts/bootstrap/modal.js',
            'resources/components/bootstrap-sass/assets/javascripts/bootstrap/collapse.js',
            'resources/components/bootstrap-sass/assets/javascripts/bootstrap/transition.js',
            //'resources/components/bootstrap-sass/assets/javascripts/bootstrap/tooltip.js',
            'resources/components/bootstrap-sweetalert/lib/sweet-alert.js',
        ],

        'forms/sortable': [
            'resources/components/jquery-ui/jquery-ui.js',
            'resources/components/nested-sortable/jquery.nested-sortable.js',
        ],
        'forms/dates': [
            'resources/components/moment/min/moment.min.js',
            'resources/components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
        ],
        'forms/tinymce': [
            'resources/components/tinymce/tinymce.min.js',
            'resources/components/tinymce/jquery.tinymce.min.js',
        ],
        'forms/upload': [
            'resources/components/bootstrap-fileinput/js/fileinput.js',
            'resources/components/bootstrap-fileinput/themes/fa/fa.js',
        ],
        'forms/json': [
            'resources/components/jsoneditor/dist/jsoneditor.js',
        ],
        'forms/multiselect': [
            'resources/components/bootstrap-select/js/bootstrap-select.js',
        ],
        'forms/tags': [
            'resources/components/bootstrap-tagsinput/src/bootstrap-tagsinput.js',
        ]
    }
};
