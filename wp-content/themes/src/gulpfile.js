require('dotenv').config() // load .env file into process.env
const { src, dest, series, parallel } = require('gulp');
const webpack = require('webpack-stream'); // gulp task runner with webpack bundler
const replace = require('gulp-replace'); // dynamically create the style.css file
const browserSync = require('browser-sync').create(); // inject any asset changes
const argv = require('yargs').argv; // used by devops.js: (--mode=git|sync|dev|build)
const {
  project, // variable filenames, paths, etc
  update_git_config, // takes info in .env and configures .git/config
  package_info, // dynamically create the WordPress style.css
  versioned_deployment_pkg, // for transfer to the web server
} = require('./project-settings');

// --- Gulp4 Tasks --- //
function do_webpack() {
  return src([project.scripts.entry, project.styles.entry])
    .pipe(webpack( require('./webpack.config.js') ))
    .pipe(dest(`assets`));
}

function init_browserSync(done) {
  browserSync.init({
    proxy: project.dev_vhost,
    open: 'external',
    files: [
      './assets/' + project.scripts.filename, 
      './assets/' + project.styles.filename
    ],
    snippetOptions: {
      rule: {
        match: /<\/body>/i,
        fn: (snippet, match) => {
          return snippet + match;
        }
      }
    },
    open: false,
    notify: false,
    ghostMode: true,
  });
  done();
}

function prepare_deployment() {
  // dont upload these to the web server
  let list_ignore = [
    '!node_modules',
    '!node_modules/**/*',
    '!.env',
    '!style.css',
  ];
  return src(['./**/*'].concat(list_ignore))
    .pipe(dest(versioned_deployment_pkg));
}

function generate_wordpress_meta() {
  // dynamically generate the style.css file
  return src('./style.css')
    .pipe(replace(/\%_VERSION_\%/g, package_info.version))
    .pipe(replace(/\%_NAME_\%/g, package_info.name))
    .pipe(replace(/\%_AUTHOR_\%/g, package_info.author))
    .pipe(dest(versioned_deployment_pkg));
}

// --- Main --- //
switch (argv.mode) {
  case 'git':
    exports.default = series(
      update_git_config,
    );
    break;
  case 'dev':
    exports.default = parallel(
      init_browserSync,
      do_webpack,
    );
    break;
  case 'build':
    exports.default = series(
      do_webpack,
    );
    break;
  case 'deploy':
    exports.default = parallel(
      generate_wordpress_meta,
      prepare_deployment,
    );
    break;
}