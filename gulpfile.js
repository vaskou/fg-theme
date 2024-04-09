import gulp from 'gulp';
import * as dartSass from 'sass';
import gulpSass from 'gulp-sass';
import cleanCSS from 'gulp-clean-css';
import browserSyncInit from 'browser-sync';
import concat from 'gulp-concat';
import minify from 'gulp-minify';
import {deleteAsync} from 'del';
import autoprefixer from "gulp-autoprefixer";
import sourcemaps from 'gulp-sourcemaps';
import rename from 'gulp-rename';
import replace from "gulp-replace";
import {config} from './gulp.config.js';

const sass = gulpSass(dartSass);
const browserSync = browserSyncInit.create();

gulp.task('clean:output', async function () {
    return await deleteAsync([
        'style.css',
        'style.min.css',
        'assets/js/**/*'
    ]);
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

export default gulp.task('default', gulp.series('build', 'watch:changes'));
