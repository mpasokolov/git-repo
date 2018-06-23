var gulp = require('gulp');

var path = require('path');
var sass = require('gulp-sass');
var browserSync = require('browser-sync');
var minifyCss = require('gulp-minify-css');
var minifyHtml = require('gulp-minify-html');
var minifyJs = require('gulp-uglify');

var src = {
  sass: path.resolve(__dirname, 'SASS', '*.sass'),
  js: path.resolve(__dirname, 'src', 'js', '*.js'),
  source: path.resolve(__dirname, 'src', '*.html')
};

var dest = {
  sass: path.resolve(__dirname, 'css'),
  root: path.resolve(__dirname),
  js: path.resolve(__dirname, 'js')
};

gulp.task('js', function () {
  gulp.src(src.js)
    .pipe(minifyJs())
    .pipe(gulp.dest(dest.js))
    .pipe(browserSync.reload({
      stream: true
    }))
});

gulp.task('html', function () {
  gulp.src(src.source)
    .pipe(minifyHtml())
    .pipe(gulp.dest(dest.root))
    .pipe(browserSync.reload({
      stream: true
    }))
});

gulp.task('sass', function () {
  gulp.src(src.sass)
    .pipe(sass())
    .pipe(minifyCss())
    .pipe(gulp.dest(dest.sass))
    .pipe(browserSync.reload({
      stream: true
    }))
});

gulp.task('browserSync', function () {
  browserSync({
    server: {
      baseDir: dest.root
    }
  })
});

gulp.task('watch', ['browserSync'], function () {
  gulp.watch(src.sass, ['sass']);
  gulp.watch(src.source, ['html']);
  gulp.watch(src.js, ['js']);
});