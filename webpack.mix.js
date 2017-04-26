const { mix } = require('laravel-mix');
let webpack = require('webpack');

mix.options({processCssUrls: false}); //prevents copying of fonts/images, which we currently do manually
mix.setPublicPath('public');
mix.setResourceRoot('resources/assets');

mix.webpackConfig({
    externals: {
        // require("jquery") is external and available
        //  on the global var jQuery
        "jquery": "jQuery"
    },
    plugins: [
        new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
        new webpack.IgnorePlugin(/^brace$/, /jsoneditor/),
    ],
    module: {
        loaders: [
            {
                test: require.resolve('tinymce/tinymce'),
                loaders: [
                    'imports?this=>window',
                    'exports?window.tinymce'
                ]
            },
            {
                test: /tinymce\/(themes|plugins)\//,
                loaders: [
                    'imports?this=>window'
                ]
            }
        ]
    },
    resolve: {
        alias: {
            'jquery-ui': 'jquery-ui/ui/widgets',
            'soda-jquery': 'jquery'
        }
    }
});

mix.sass('resources/assets/scss/application.scss', 'public/css')
    .sass('resources/assets/scss/bootstrap.scss', 'public/css')
    .sass('resources/assets/scss/fonts.scss', 'public/css')
    .sass('resources/assets/scss/plugins.scss', 'public/css')
    .sass('resources/assets/scss/themes/default.scss', 'public/css/themes')
    .sass('resources/assets/scss/themes/lime.scss', 'public/css/themes')
    .sass('resources/assets/scss/themes/strawberry.scss', 'public/css/themes')
    .sass('resources/assets/scss/themes/grape.scss', 'public/css/themes')
    .js('resources/assets/js/application.js', 'public/js')
    .js('resources/assets/js/page-tree.js', 'public/js')
    .js('resources/assets/js/wave.js', 'public/js')
    .js('resources/assets/js/core.js', 'public/js')
    .js('resources/assets/js/forms/sortable.js', 'public/js/forms')
    .js('resources/assets/js/forms/dates.js', 'public/js/forms')
    .js('resources/assets/js/forms/tinymce.js', 'public/js/forms')
    .js('resources/assets/js/forms/upload.js', 'public/js/forms')
    .js('resources/assets/js/forms/multiselect.js', 'public/js/forms')
    .js('resources/assets/js/forms/json.js', 'public/js/forms')
    .js('resources/assets/js/forms/slugs.js', 'public/js/forms')
    .copyDirectory('node_modules/bootstrap/fonts', 'public/fonts/bootstrap')
    .copyDirectory('node_modules/font-awesome/fonts', 'public/fonts/font-awesome')
    .copyDirectory('node_modules/mdi/fonts', 'public/fonts/mdi')
    .copyDirectory('node_modules/bootstrap-fileinput/img', 'public/components/bootstrap-fileinput/img')
    .copyDirectory('node_modules/jsoneditor/dist/img', 'public/components/jsoneditor/img')
    .copyDirectory('node_modules/tinymce/plugins', 'public/components/tinymce/plugins')
    .copyDirectory('node_modules/tinymce/skins', 'public/components/tinymce/skins')
    .copyDirectory('node_modules/tinymce/themes', 'public/components/tinymce/themes')
    .copyDirectory('resources/assets/img', 'public/img')
    .copyDirectory('resources/assets/js', 'public/js');
