const dotenvExpand = require('dotenv-expand');
dotenvExpand(require('dotenv').config({ path: '../../.env'/*, debug: true*/}));

const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/assets/js/app.js', 'academic/js/academic.js')
    .sass( __dirname + '/Resources/assets/sass/app.scss', 'academic/css/academic.css')
    .copyDirectory( __dirname + '/Resources/assets/plugins', '../../public/academic/plugins');

if (mix.inProduction()) {
    mix.version();
}
