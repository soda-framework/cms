var gulp = require('gulp');
var del = require('del');
var chalk = require('chalk');
var config = require('./gulpconfig.js');
$ = require('gulp-load-plugins')();

gulp.task('publish:css', function () {
    checkCleanFolder('css');
    var tasks = [];
    var stream = gulp.src(fixPath(config.assets.folder) + fixPath(getSetting(config.assets, defaults.assets, 'css.path')) + getSetting(config.assets, defaults.assets, 'css.wildcard'))
        .pipe($.plumber(logError))
        .pipe($.if(getSetting(config.assets, defaults.assets, 'css.sourcemaps'), $.sourcemaps.init())) //initialize sourcemap
        .pipe( //compile sass
            $.sass({
                outputStyle: getSetting(config.assets, defaults.assets, 'css.output'),
                precision: getSetting(config.assets, defaults.assets, 'css.precision')
            })
        )
        .pipe($.if(getSetting(config.assets, defaults.assets, 'css.autoprefix'), $.autoprefixer({
            browsers: ['ie >= 8', 'Chrome >= 14', 'ff >= 14', 'Safari >= 5.1', 'Opera >= 12', 'last 2 iOS versions', 'last 2 Android versions'],
        }))) //handles browser support
        .pipe($.if(getSetting(config.assets, defaults.assets, 'css.min_suffix'), $.rename({suffix: ".min"}))) //append .min to filename
        .pipe($.if(getSetting(config.assets, defaults.assets, 'css.show_size'), $.size({
            showFiles: true,
            title: 'Minified: ',
            pretty: true
        }))) //output minified size
        .pipe($.if(getSetting(config.assets, defaults.assets, 'css.sourcemaps'), $.sourcemaps.write('./'))); //generate sourcemap files

    config.publish.map(function (output) { //write for each output folder
        tasks.push(
            stream.pipe(gulp.dest(fixPath(output.folder) + getSetting(output, defaults.publish, 'css.path')))
                .pipe($.notify({message: 'CSS published to ' + './' + fixPath(output.folder) + getSetting(output, defaults.publish, 'css.path'), onLast: true}))
        );
    });

    //execute task array
    return $.merge(tasks);
});

gulp.task('publish:js', function () {
    checkCleanFolder('js');
    var tasks = [];
    var stream = gulp.src(fixPath(config.assets.folder) + fixPath(getSetting(config.assets, defaults.assets, 'js.path')) + getSetting(config.assets, defaults.assets, 'js.wildcard'))
        .pipe($.plumber(logError))
        .pipe($.if(getSetting(config.assets, defaults.assets, 'js.show_size'), $.size({
            showFiles: true,
            title: 'Original: ',
            pretty: true
        }))) //output original size
        .pipe($.if(getSetting(config.assets, defaults.assets, 'js.sourcemaps'), $.sourcemaps.init())) //initialize sourcemap
        .pipe($.uglify({mangle: true})) //minify js
        .pipe($.if(getSetting(config.assets, defaults.assets, 'js.min_suffix'), $.rename({suffix: ".min"}))) //append .min to filename
        .pipe($.if(getSetting(config.assets, defaults.assets, 'js.show_size'), $.size({
            showFiles: true,
            title: 'Minified: ',
            pretty: true
        }))) //output minified size
        .pipe($.if(getSetting(config.assets, defaults.assets, 'js.sourcemaps'), $.sourcemaps.write('./'))); //generate sourcemap files

    config.publish.map(function (output) { //write for each output folder
        tasks.push(
            stream.pipe(gulp.dest(fixPath(output.folder) + getSetting(output, defaults.publish, 'js.path')))
                .pipe($.notify({message: 'JS published to ' + './' + fixPath(output.folder) + getSetting(output, defaults.publish, 'js.path'), onLast: true}))
        );
    });

    //execute task array
    return $.merge(tasks);
});

gulp.task('publish:img', function () {
    checkCleanFolder('img');
    var tasks = [];

    config.publish.map(function (output) { //write for each output folder
        tasks.push(
            gulp.src('./' + fixPath(config.assets.folder) + fixPath(getSetting(config.assets, defaults.assets, 'img.path')) + getSetting(config.assets, defaults.assets, 'img.wildcard'))
                .pipe(gulp.dest('./' + fixPath(output.folder) + getSetting(output, defaults.publish, 'img.path')))
                .pipe($.notify({message: 'Images published to ' + './' + fixPath(output.folder) + getSetting(output, defaults.publish, 'img.path'), onLast: true}))
        );
    });

    //execute task array
    return $.merge(tasks);
});

gulp.task('publish:font', function () {
    checkCleanFolder('font');
    var tasks = [];

    config.publish.map(function (output) { //write for each output folder
        tasks.push(
            gulp.src(fixPath(config.assets.folder) + fixPath(getSetting(config.assets, defaults.assets, 'font.path')) + getSetting(config.assets, defaults.assets, 'font.wildcard'))
                .pipe(gulp.dest(fixPath(output.folder) + getSetting(output, defaults.publish, 'font.path')))
                .pipe($.notify({message: 'Fonts published to ' + './' + fixPath(output.folder) + getSetting(output, defaults.publish, 'font.path'), onLast: true}))
        );
    });

    //execute task array
    return $.merge(tasks);
});

gulp.task('publish:svg', function () {
    var stream = gulp.src(fixPath(config.assets.folder) + fixPath(getSetting(config.assets, defaults.assets, 'svg.path')) + getSetting(config.assets, defaults.assets, 'svg.wildcard'));
    stream = stream.pipe($.iconfontCss({
        fontName: getSetting(config.assets, defaults.assets, 'svg.name'),
        path: getSetting(config.assets, defaults.assets, 'svg.template'),
        targetPath: '../../' + getSetting(config.assets, defaults.assets, 'css.path') + '/font/_' + getSetting(config.assets, defaults.assets, 'svg.name') + '.scss',
        fontPath: './../' + getSetting(config.assets, defaults.assets, 'font.path') + '/' + getSetting(config.assets, defaults.assets, 'svg.name') + '/'
    }));
    stream = stream.pipe($.iconfont({
        formats: getSetting(config.assets, defaults.assets, 'svg.formats'),
        //normalize: true,
        fontName: getSetting(config.assets, defaults.assets, 'svg.name')
    }));
    stream = stream.pipe(gulp.dest(fixPath(config.assets.folder) + fixPath(getSetting(config.assets, defaults.assets, 'font.path')) + fixPath(getSetting(config.assets, defaults.assets, 'svg.name'))));

    stream.pipe($.notify({message: 'SVG font generated!', onLast: true}));

    return stream; //tell gulp that async stream task is complete
});

gulp.task('publish:components', function () {
    checkCleanFolder('components');
    var tasks = [];

    //run per package group
    objMap(config.components, function (pkg, pkg_name) {
        var stream = gulp.src(pkg)
            .pipe($.plumber(logError))
            .pipe($.if(getSetting(config.assets, defaults.assets, 'components.show_size'), $.size({
                showFiles: false,
                title: pkg_name + ' original: ',
                pretty: true
            }))) //output original size
            .pipe($.if(getSetting(config.assets, defaults.assets, 'components.sourcemaps'), $.sourcemaps.init())) //initialize sourcemap
            .pipe($.concat(pkg_name + (getSetting(config.assets, defaults.assets, 'components.min_suffix') ? '.min.js' : '.js'))) //merge js files into one - name based off of package group
            .pipe($.uglify()) //now minify it
            .pipe($.if(getSetting(config.assets, defaults.assets, 'components.show_size'), $.size({
                showFiles: false,
                title: pkg_name + ' minified: ',
                pretty: true
            }))) //output minified size
            .pipe($.if(getSetting(config.assets, defaults.assets, 'components.sourcemaps'), $.sourcemaps.write('./'))); //generate sourcemap files

        //write for each output folder
        config.publish.map(function (output) {
            stream.pipe(gulp.dest(fixPath(output.folder) + getSetting(output, defaults.publish, 'components.path')))
                .pipe($.notify({message: 'Components  published to ' + './' + fixPath(output.folder) + getSetting(output, defaults.publish, 'components.path'), onLast: true}));

            //push onto task array
            tasks.push(stream);
        });
    });

    //execute task array
    return $.merge(tasks);
});

gulp.task('publish:all', function () {
    gulp.start('publish:svg');
    gulp.start('publish:font');
    gulp.start('publish:css');
    gulp.start('publish:js');
    gulp.start('publish:img');
    gulp.start('publish:components');
});

gulp.task('watch', function () {
    var base = fixPath(getSetting(config.assets, defaults.assets, 'folder'));

    //watch these directories and call the appropriate gulp functions. gulp-batch is used so that multiple files modified at once are processed in one single hit
    $.watch(base + fixPath(getSetting(config.assets, defaults.assets, 'css.path')) + getSetting(config.assets, defaults.assets, 'css.wildcard'), $.batch(function (events, done) {
        gulp.start('publish:css', done);
    }));

    $.watch(base + fixPath(getSetting(config.assets, defaults.assets, 'js.path')) + getSetting(config.assets, defaults.assets, 'js.wildcard'), $.batch(function (events, done) {
        gulp.start('publish:js', done);
    }));

    $.watch(base + fixPath(getSetting(config.assets, defaults.assets, 'img.path')) + getSetting(config.assets, defaults.assets, 'img.wildcard'), $.batch(function (events, done) {
        gulp.start('publish:img', done);
    }));

    $.watch(base + fixPath(getSetting(config.assets, defaults.assets, 'font.path')) + getSetting(config.assets, defaults.assets, 'font.wildcard'), $.batch(function (events, done) {
        gulp.start('publish:font', done);
    }));

    $.watch(base + fixPath(getSetting(config.assets, defaults.assets, 'svg.path')) + getSetting(config.assets, defaults.assets, 'svg.wildcard'), $.batch(function (events, done) {
        gulp.start('publish:svg', done);
    }));
});

//handle errors gracefully - output to console and resume script
var logError = function (err) {
    $.util.log(chalk.red('Error' + err.toString()));
    this.emit('end');
};

//Array.map functionality... for objects
var objMap = function (obj, callback) {
    var result = {};
    Object.keys(obj).forEach(function (key) {
        result[key] = callback.call(obj, obj[key], key, obj);
    });
    return result;
};

//delete all files at specified path
var cleanFolder = function (path) {
    $.util.log(chalk.yellow('Cleaning directory ' + path));
    del([path + '/**/*'], {force: true});
};

//check if folder should be cleaned - then clean it
var checkCleanFolder = function (type) {
    config.publish.map(function (output, i) {
        if (getSetting(output, defaults.publish, type + '.clean')) {
            cleanFolder(fixPath(output.folder) + getSetting(output, defaults.publish, type + '.path'));
            var map_type = Object.keys(defaults.publish)[i];
            if (getSetting(output, defaults.publish, type + '.path') == getSetting(output, defaults.publish, map_type + '.path') && type != map_type) {
                $.util.log(chalk.yellow('Notice: "' + map_type + '" was cleaned by "' + type + '" clean function. Re-publishing.'));
                gulp.start('publish:' + map_type);
            }
        }
    });
};

//access object properties by a string identifier
var accessProperties = function (object, string, safe) {
    var explodedString = string.split('.');
    for (i = 0, l = explodedString.length; i < l; i++) {
        object = object[explodedString[i]];
        if (object === undefined && safe == true) return undefined;
    }
    return object;
};

//get setting with defaults
var getSetting = function (obj, def_obj, setting) {
    var out = accessProperties(obj, setting, true);
    if (out == undefined) {
        out = accessProperties(def_obj, setting);
    }
    return out;
};

var fixPath = function(path) {
    if(path.substr(-1) === '/') {
        return path;
    }
    return path + '/';
}

//default values
var defaults = {
    assets: {
        css: {
            path: 'scss',
            wildcard: '**/*.scss',
            output: 'compressed',
            autoprefix: true,
            precision: 8,
            min_suffix: true,
            sourcemaps: true,
            show_size: true
        },
        js: {
            path: 'js',
            wildcard: '**/*.js',
            min_suffix: true,
            sourcemaps: true,
            show_size: true
        },
        img: {
            path: 'img',
            wildcard: '**/*',
        },
        svg: {
            name: 'icon-font',
            path: 'svg',
            wildcard: '**/*.svg',
            template: 'scss',
            formats: ['svg', 'ttf', 'eot', 'woff', 'woff2'],
        },
        font: {
            path: 'font',
            wildcard: '**/*.*'
        },
        components: {
            min_suffix: true,
            sourcemaps: false,
            show_size: true
        }
    },
    publish: {
        css: {
            path: 'css',
            clean: false
        },
        js: {
            path: 'js',
            clean: false
        },
        img: {
            path: 'img',
            clean: false
        },
        svg: {
            path: 'font',
            clean: false
        },
        font: {
            path: 'font',
            clean: true
        },
        components: {
            path: 'js',
            clean: true
        }
    }
};
