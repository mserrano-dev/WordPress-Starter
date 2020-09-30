require('dotenv').config(); // load .env file into process.env
const { src, dest, series, parallel } = require('gulp');
const webpack = require('webpack-stream'); // gulp task runner with webpack bundler
const named = require('vinyl-named'); // multiple named webpack entry files
const replace = require('gulp-replace'); // dynamically create the style.css file
const del = require('del'); // delete files/folders
const ncp = require('ncp').ncp; // copy directories recursively (move wp-admin/wp-include to our code base)
const fs = require('fs'); // write to filesystem
const AdmZip = require('adm-zip'); // unzip
const axios = require('axios'); // make ajax call to download latest wordpress install
const ProgressBar = require('progress'); // ascii progress indicator
const browserSync = require('browser-sync').create(); // inject any asset changes
const argv = require('yargs').argv; // used by devops.js: (--mode=git|sync|dev|build)
const {
  project, // variable filenames, paths, etc
  update_git_config, // takes info in .env and configures .git/config
  package_info, // dynamically create the WordPress style.css
  versioned_deployment_pkg, // for transfer to the web server
} = require('./project-settings');

// --- Gulp4 Tasks --- //
function wordpress_installSetUp() {
  // clean up current installation
  return del([
    'wp-admin/',
    'wp-includes/',
  ]);
}

function wordpress_downloadLatest(done) {
  // clean up current installation
  del([
    'wp-admin/',
    'wp-includes/',
  ]);

  // download the latest
  console.log('Connecting …');
  axios({
    url: 'https://wordpress.org/latest.zip',
    method: 'GET',
    responseType: 'stream',
    headers: {
      'Content-Type': 'application/zip'
    }
  }).then((response) => {
    const totalLength = response.headers['content-length'];
    const progressBar = new ProgressBar('-> downloading [:bar] :percent :etas', {
      width: 40,
      complete: '=',
      incomplete: ' ',
      renderThrottle: 1,
      total: parseInt(totalLength)
    });
    response.data.on('data', (chunk) => progressBar.tick(chunk.length));
    response.data.pipe(fs.createWriteStream('wordpress.zip'));
    response.data.on('end', done);
  });
}

function wordpress_unzipAndInstall(done) {
  var zip = new AdmZip("wordpress.zip");
  zip.extractAllTo('.');
  del([
    'wordpress/wp-content'
  ]);

  ncp.limit = 16;

  ncp('wordpress', '.', function (err) {
    done();
  });
}

function wordpress_installTearDown() {
  return del([
    'wordpress.zip',
    'wordpress'
  ]);
}

function do_webpack() {
  return src([`wp-content/themes/src/_dev/${project.entry.main}.ts`, `wp-content/themes/src/_dev/${project.entry.admin}.ts`])
    .pipe(named())
    .pipe(webpack(require('./webpack.config.js')))
    .pipe(dest(`wp-content/themes/src/assets`));
}

function init_browserSync(done) {
  browserSync.init({
    proxy: project.dev_vhost,
    open: 'external',
    files: [
      `assets/${project.entry.admin}.min.js`,
      `assets/${project.entry.main}.min.css`,
      `assets/${project.entry.admin}.min.css`,
      '**/*.*',
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
    '!wp-content/themes/src/style.css',
  ];
  return src(['wp-content/themes/src/**/*'].concat(list_ignore))
    .pipe(dest(versioned_deployment_pkg));
}

function generate_wordpress_meta() {
  // dynamically generate the style.css file
  return src('wp-content/themes/src/style.css')
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
  case 'updateWP':
    exports.default = series(
      wordpress_installSetUp,
      wordpress_downloadLatest,
      wordpress_unzipAndInstall,
      wordpress_installTearDown
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