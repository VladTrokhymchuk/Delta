import glob_entries from 'webpack-glob-entries';
import webpack from 'webpack-stream';

export const js = () => {
  // let entries = {};
  // glob.sync(app.path.src.js).map(function (file) {
  //   entries[file] = file;
  // });

  return app.gulp
    .src(app.path.src.js, { sourcemaps: app.isDev })
    .pipe(
      app.plugins.plumber(
        app.plugins.notify.onError({
          title: 'JS',
          message: 'Error: <%= error.message %>',
        })
      )
    )
    .pipe(
      webpack({
        entry: glob_entries('./dev/js/*.js'),
        mode: 'production',
      })
    )
    .pipe(app.gulp.dest('./build/js/'))

    .pipe(
      webpack({
        entry: glob_entries('./dev/js/pages/*.js'),
        mode: 'production',
      })
    )
    .pipe(app.gulp.dest('./build/js/pages/'))

    .pipe(
      webpack({
        entry: glob_entries('./dev/js/pages/front-page/*.js'),
        mode: 'production',
      })
    )
    .pipe(app.gulp.dest('./build/js/pages/front-page/'))

     .pipe(
      webpack({
        entry: glob_entries('./dev/js/pages/single-news/*.js'),
        mode: 'production',
      })
    )
    .pipe(app.gulp.dest('./build/js/pages/single-news/'))

    .pipe(
      webpack({
        entry: glob_entries('./dev/js/modules/*.js'),
        mode: 'production',
      })
    )
    .pipe(app.gulp.dest('./build/js/modules/'))

    .pipe(
      webpack({
        entry: glob_entries('./dev/js/libs/*.js'),
        mode: 'production',
      })
    )
    .pipe(app.gulp.dest('./build/js/libs/'))

    .pipe(app.plugins.browsersync.stream());
};
