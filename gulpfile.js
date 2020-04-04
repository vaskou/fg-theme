const gulp = require('gulp');
const sass = require('gulp-sass');
const cleanCSS = require('gulp-clean-css');
const browserSync = require('browser-sync').create();
const concat = require('gulp-concat');
const minify = require('gulp-minify');
const fileinclude = require('gulp-file-include');
const gtm = require('gulp-google-tag-manager');
const version = require('gulp-version-number');
const del = require('del');
const autoprefixer = require('gulp-autoprefixer');

const versionConfig = {
    'value': '%MDS%',
    'append': {
        'key': 'v',
        'cover': 1,
        'to': ['css', 'js'],
    },
};

const gtmID = 'GTM-NQQQQ9F';

gulp.task('clean:output', function () {
    return del('public/**/*');
});

gulp.task('build:htaccess', function () {
    return gulp.src('./src/.htaccess')
        .pipe(gulp.dest('./public/'));
});

gulp.task('build:mailgun', function () {
    return gulp.src('./src/mail_contact/**/*')
        .pipe(gulp.dest('./public/mail_contact'));
});

gulp.task('build:images', function () {
    return gulp.src('./src/images/**/*')
        .pipe(gulp.dest('./public/images'));
});

gulp.task('build:fonts', function () {
    return gulp.src('./src/fonts/**/*')
        .pipe(gulp.dest('./public/fonts'));
});

gulp.task('build:scripts', function () {

    return gulp.src([
        './node_modules/jquery/dist/jquery.min.js',
        './node_modules/jquery-validation/dist/jquery.validate.min.js',
        './src/js/bootstrap.min.js',
        './src/js/popper.min.js',
        './src/js/owl.carousel.min.js',
        './src/js/jquery.counterup.min.js',
        './src/js/waypoints.min.js',
        './src/js/smoothscroll.js',
        './src/js/custom.js',
        './src/js/contact_form.js'
    ])
        .pipe(concat('scripts.js'))
        .pipe(minify({
            ext: {
                min: '.js'
            },
            noSource: true
        }))
        .pipe(gulp.dest('./public/js'));

});


gulp.task('build:styles', function () {

    return gulp.src('./src/sass/styles.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
            cascade: false
        }))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('./public/css'))
        .pipe(browserSync.stream());

});


gulp.task('build:html', function () {

    return gulp.src('./src/**/*.html', {ignore: ['./src/partials/*']})
        .pipe(gtm({gtmId: gtmID}))
        .pipe(fileinclude({
            prefix: '@@',
            basepath: '@file'
        }))
        .pipe(version(versionConfig))
        .pipe(gulp.dest('./public'))
        .pipe(browserSync.reload({
            stream: true
        }));

});

gulp.task('build', gulp.series('clean:output', 'build:htaccess', 'build:mailgun', 'build:images', 'build:fonts', 'build:styles', 'build:scripts', 'build:html'));

gulp.task('watch:changes', function (cb) {

    browserSync.init({
        proxy: "localhost.asfaleies-haronis.gr",
        reloadDelay: 2000
    });

    gulp.watch('./src/mail_contact/**/*', gulp.series('build:mailgun'));
    gulp.watch('./src/sass/*.scss', gulp.series('build:styles'));
    gulp.watch(['./src/js/*.js', './src/**/*.html'], gulp.series('build'));

    cb();

});

exports.default = gulp.series('build', 'watch:changes');
