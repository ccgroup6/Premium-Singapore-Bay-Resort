'use strict';

const gulp         = require('gulp');
const plumber      = require('gulp-plumber');
const notify       = require('gulp-notify');
const sourcemaps   = require('gulp-sourcemaps');
const sass         = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const gcmq         = require('gulp-group-css-media-queries');
const cleanCSS     = require('gulp-clean-css');
const bro          = require('gulp-bro');
const babelify     = require('babelify');
const uglify       = require('gulp-uglify');
const rename       = require('gulp-rename');
const potgen       = require('gulp-wp-pot');
const browserSync  = require('browser-sync').create();
const del          = require('del');
var rtlcss = require('gulp-rtlcss');

/**
 * Handle errors and alert the user.
 */
function handleErrors() {
  let args = Array.prototype.slice.call(arguments);

  notify.onError({
    title: 'Task Failed! See console.',
    message: '<%= error.message %>',
  }).apply(this, args);

  // Prevent the 'watch' task from stopping
  this.emit('end');
}

gulp.task('scss', () => {
  return gulp.src('src/sass/*.scss')
    .pipe(plumber({ errorHandler: handleErrors }))
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(gcmq())
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('dist/css'))
    .pipe(browserSync.stream({ match: '**/*.css' }));
});

gulp.task('minify:css', () => {
  return gulp.src(['dist/css/*.css', '!dist/css/*.min.css'])
    .pipe(plumber({ errorHandler: handleErrors }))
    .pipe(cleanCSS())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('dist/css'));
});

gulp.task('rtlcss', function() {
  return gulp.src('./dist/css/main.css')
    .pipe(rtlcss())
    .pipe(rename({ suffix: '-rtl' }))
    .pipe(gulp.dest('dist/css'));
});

gulp.task('watch', () => {
  browserSync.init({
    proxy: 'fleurdesel.local',
  });

  gulp.watch('src/sass/**/*.scss', gulp.parallel('scss'));
});

gulp.task('css', gulp.series(['scss', 'rtlcss', 'minify:css']));
gulp.task('default', gulp.series(['css']));
