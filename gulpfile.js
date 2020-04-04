const gulp = require('gulp');
const sass = require('gulp-sass');
const cleanCSS = require('gulp-clean-css');
const browserSync = require('browser-sync').create();
const concat = require('gulp-concat');
const minify = require('gulp-minify');
const del = require('del');
const autoprefixer = require('gulp-autoprefixer');

gulp.task('clean:output', function () {
    return del('assets/**/*');
});

gulp.task('build:scripts', function () {

    return gulp.src([
        './node_modules/uikit/dist/js/uikit.js',
        './node_modules/uikit/dist/js/uikit-icons.js',
        './src/js/scripts.js'
    ])
        .pipe(concat('scripts.js'))
        .pipe(minify({
            ext: {
                min: '.js'
            },
            noSource: true
        }))
        .pipe(gulp.dest('./assets/js'));

});


gulp.task('build:styles', function () {

    return gulp.src('./src/sass/styles.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
            cascade: false
        }))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('./assets/css'))
        .pipe(browserSync.stream());

});

gulp.task('build', gulp.series('clean:output', 'build:styles', 'build:scripts'));

gulp.task('watch:changes', function (cb) {

    browserSync.init({
        proxy: "new.fremeditiguitars.com",
        reloadDelay: 2000
    });

    gulp.watch('./src/sass/*.scss', gulp.series('build:styles'));
    gulp.watch(['./src/js/*.js'], gulp.series('build'));

    cb();

});

exports.default = gulp.series('build', 'watch:changes');
