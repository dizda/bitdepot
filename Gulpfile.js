'use strict';

var gulp          = require('gulp'),
    sass          = require('gulp-ruby-sass'),
    minifycss     = require('gulp-minify-css'),
    uglify        = require('gulp-uglify'),
    del           = require('del'),
    useref        = require('gulp-useref'),
    gulpif        = require('gulp-if'),
    templateCache = require('gulp-angular-templatecache'),
    minifyHTML    = require('gulp-minify-html'),
    concat        = require('gulp-concat');


/**
 * Execute all taks
 */
gulp.task('default', ['clean'], function() {
    gulp.start('sass', 'html', 'angular-templates', 'concat-scripts', 'copy-statics');
});

/**
 * Remove 'dist/' directory before starting
 */
gulp.task('clean', function(cb) {
    del(['dist/'], cb);
});

gulp.task('sass', function () {
    return sass('public/css', {sourcemap: false})
        .on('error', function (err) {
            console.error('Error!', err.message);
        })
        .pipe(gulp.dest('public/css'))
    ;
});

/**
 * - Waiting for SASS to be done
 * - Take all CSS and JS specified in index.html
 * - minify css
 * - concat them
 * - minify the index.html
 */
gulp.task('html', ['sass'], function () {
    //var assets = useref.assets({searchPath: ['dist', 'public']});
    var assets = useref.assets();

    return gulp.src('./public/index.html')
        .pipe(assets)
        .pipe(gulpif('*.js', uglify()))
        .pipe(gulpif('*.css', minifycss()))
        .pipe(assets.restore())
        .pipe(useref())
        .pipe(gulpif('*.html', minifyHTML({
            conditionals: true // do not remove IE conditional comments
        })))
        .pipe(gulp.dest('./dist'))
    ;
});

/**
 * Copy statics to dist directory
 */
gulp.task('copy-statics', ['html'], function () {
    gulp.src('public/components/fontawesome/fonts/*')
        .pipe(gulp.dest('dist/fonts'))
    ;
    gulp.src('public/fonts/*')
        .pipe(gulp.dest('dist/fonts'))
    ;
    gulp.src('public/components/html5shiv/dist/html5shiv.min.js')
        .pipe(gulp.dest('dist/components/html5shiv/dist'))
    ;
    gulp.src('public/components/respond/dest/respond.min.js')
        .pipe(gulp.dest('dist/components/respond/dest'))
    ;
    gulp.src('public/components/Chart.js/Chart.min.js')
        .pipe(gulp.dest('dist/components/Chart.js'))
    ;
    gulp.src('public/images/*')
        .pipe(gulp.dest('dist/images'))
    ;
    gulp.src('public/.htaccess')
        .pipe(gulp.dest('dist'))
    ;
});

/**
 * Extract all angular templates and inject them into JS angular-cache
 */
gulp.task('angular-templates', function () {
    return gulp.src('public/js/**/*.html')
        .pipe(minifyHTML())
        .pipe(templateCache('assets/templates.js', {
            module: 'app',     // use app module instead of templates
            standalone: false,
            root: '/js/'       // specify that templates name begins with /js/ directory
        }))
        .pipe(gulp.dest('dist'))
    ;
});

/**
 * Concat build.js and angular-templates
 */
gulp.task('concat-scripts', ['html', 'angular-templates'], function() {
    return gulp.src(['./dist/assets/build.js', './dist/assets/templates.js'])
        .pipe(concat('build.js'))
        .pipe(gulp.dest('./dist/assets/'));
});

//// Images
//gulp.task('images', function() {
//    return gulp.src('src/images/**/*')
//        .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
//        .pipe(gulp.dest('dist/images'))
//        .pipe(notify({ message: 'Images task complete' }));
//});