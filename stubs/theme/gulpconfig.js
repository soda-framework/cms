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
            template:       'resources/scss/font/_font.tpl',
            formats:        ['ttf', 'eot', 'woff', 'woff2', 'svg']
        },
        font: {
            path:           'fonts',
        }
    },

    //array of directories that will be published to
    publish: [
        {
            folder:         'public',
        },
        {
            folder:         '../../public/themes/soda-example',
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
            },
            font: {
                path:       'fonts',
            }
        }
    ],

    //list all bower components, separated into arrays of packages to be concatenated
    components: {
        scripts : [
            'resources/components/jquery/dist/jquery.js'
        ]
    }
};
