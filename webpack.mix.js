const mix = require('laravel-mix');
const config = require('./webpack.config');


mix.webpackConfig(config);

mix
  .ts('resources/js/main.js', 'public/js/')
  .ts('resources/js/libs/rrweb/src/record/index.ts', 'public/js/record.js')
  .extract([
    'vue',
    'axios',
  ])
  .sass('resources/sass/index.scss', 'public/css/app.css', {
    implementation: require('node-sass'),
  })
  .options({
    processCssUrls: false,
  })

mix.version();

if (!mix.inProduction()) {

  // Development settings
  mix.sourceMaps()
    .webpackConfig({
      devtool: 'cheap-eval-source-map' // Fastest for development
    });
}
