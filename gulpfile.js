const gulp = require('gulp');
const sass = require('gulp-sass');
const cleanCSS = require('gulp-clean-css');
const browserSync = require('browser-sync').create();
const concat = require('gulp-concat');
const minify = require('gulp-minify');
const del = require('del');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const replace = require('gulp-replace');
const config = require( './gulp.config.js' );


gulp.task('clean:output', function () {
    del('style.css');
    del('style.min.css');
    return del('assets/js/**/*');
});

gulp.task('build:scripts', function () {

    return gulp.src([
        './node_modules/uikit/dist/js/uikit.js',
        './node_modules/uikit/dist/js/uikit-icons.js',
        './src/js/scripts.js'
    ])
        .pipe(concat('scripts.js'))
        .pipe(gulp.dest(config.jsDestination))
        .pipe(browserSync.stream())
        .pipe(minify({
            ext: {
                min: '.min.js'
            },
            noSource: true
        }))
        .pipe(gulp.dest(config.jsDestination))
        .pipe(browserSync.stream());

});


gulp.task('build:styles', function () {

    return gulp.src(['./src/sass/style.scss'], {allowEmpty: true})
        .pipe(replace('@charset "UTF-8";', ''))
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(sass({
            outputStyle: config.outputStyle,
        }).on('error', sass.logError))
        .pipe(sourcemaps.write({includeContent: false, sourceRoot: './src/sass/'}))
        .pipe(gulp.dest(config.cssDestination))
        .pipe(browserSync.stream())
        .pipe(autoprefixer({
            cascade: false
        }))
        .pipe(rename({suffix: '.min'}))
        .pipe(cleanCSS())
        .pipe(gulp.dest(config.cssDestination))
        .pipe(browserSync.stream());

});

gulp.task('build', gulp.series('clean:output', 'build:styles', 'build:scripts'));

gulp.task('watch:changes', function (cb) {

    browserSync.init({
        proxy: config.projectURL,
        reloadDelay: 2000,
        browser: config.browser,
    });

    gulp.watch('./src/sass/*.scss', gulp.series('build:styles'));
    gulp.watch(['./src/js/*.js'], gulp.series('build'));

    cb();

});

exports.default = gulp.task('default', gulp.series('build', 'watch:changes'));
