var gulp = require('gulp');

var path = require('path');
var sass = require('gulp-sass');
var browserSync = require('browser-sync');
var minifyCss = require('gulp-minify-css');
var minifyHtml = require('gulp-minify-html');

var src = {
  sass: path.resolve(__dirname, 'SASS', '*.sass'),
  js: path.resolve(__dirname, 'js', '*.js'),
  html: path.resolve(__dirname, '*.html')
};

var dest = {
  sass: path.resolve(__dirname, 'css'),
  root: path.resolve(__dirname)
};

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
  gulp.watch(src.js, browserSync.reload);
  gulp.watch(src.html, browserSync.reload);
});