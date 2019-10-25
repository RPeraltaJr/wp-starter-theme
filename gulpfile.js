/*
* ------------------------------------------------------------------------------
* Gulpfile.js
* https://gulpjs.com/
* ------------------------------------------------------------------------------
*/

const gulp = require('gulp');
// const sourcemaps = require('gulp-sourcemaps');
const uglify = require('gulp-uglify');
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');
const sass = require('gulp-sass');
const rename = require('gulp-rename');
const sassGlob = require('gulp-sass-glob');
const browserify = require('browserify');
const babelify = require('babelify');
const source = require('vinyl-source-stream');
const buffer = require('vinyl-buffer');
const es = require('event-stream');

const paths = {
  styles: {
    scssInput: 'assets/uncompiled/scss/*.scss',
    modules: 'components/**/**/*.scss',
    cssInput: 'assets/uncompiled/css/*.css',
    cssDest: 'assets/uncompiled/css/',
    cssDestMin: 'assets/build/css/',
  },
  scripts: {
    inputs: [
      './assets/uncompiled/js/page-home.js',
    ],
    modules: 'assets/uncompiled/js/modules/*.js',
    dest: 'assets/build/js/',
  },
};

const sassOptions = {
  errLogToConsole: true,
  outputStyle: 'expanded',
};

// * Compiles Sass and minifies CSS
function styles() {
  return gulp
    .src(paths.styles.scssInput)
    .pipe(sassGlob())
    .pipe(sass(sassOptions)).on('error', sass.logError)
    .pipe(autoprefixer({
      grid: true,
    }))
    .pipe(gulp.dest(paths.styles.cssDest))
    .pipe(cleanCSS())
    .pipe(rename({
      suffix: '.min',
    }))
    .pipe(gulp.dest(paths.styles.cssDestMin));
}
exports.styles = styles;

// * Compresses Scripts
async function scripts() {
  const tasks = await paths.scripts.inputs.map((entry) => {
    return (
      browserify({
        entries: [entry],
        debug: true,
      })
      .transform(babelify, {
        presets: ['@babel/preset-env'],
      })
      .bundle()
      .pipe(source(entry))
      .pipe(buffer())
      // .pipe(sourcemaps.init())
      .pipe(uglify())
      // .pipe(sourcemaps.write('./'))
      .pipe(rename({
        dirname: '',
        extname: '.min.js',
      }))
      .pipe(gulp.dest(paths.scripts.dest))
    );
  });

  return es.merge.apply(null, tasks);
}
exports.scripts = scripts;

// * Watch Task
function watch() {
  gulp.watch(paths.styles.scssInput, styles).on('all', (event, path, stats) => {
    console.log(`File ${path} was ${event}, running tasks...`);
  });
  gulp.watch(paths.styles.modules, styles).on('all', (event, path, stats) => {
    console.log(`File ${path} was ${event}, running tasks...`);
  });
  gulp.watch(paths.scripts.inputs, scripts).on('all', (event, path, stats) => {
    console.log(`File ${path} was ${event}, running tasks...`);
  });
  gulp.watch(paths.scripts.modules, scripts).on('all', (event, path, stats) => {
    console.log(`File ${path} was ${event}, running tasks...`);
  });
}
exports.watch = watch;

const build = gulp.parallel(styles, scripts, watch);

gulp.task('default', build);