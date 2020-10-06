const mix = require('laravel-mix');

mix.minTemplate = require('laravel-mix-template-minifier');

if (mix.inProduction()) {
  mix.minTemplate('storage/framework/views/*.php', 'storage/framework/views/');
}

const config = {
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/assets/js'),
    },
  },
};

mix
  .webpackConfig(config)
  .js('resources/assets/js/app.js', 'public/js')
  .js('resources/assets/js/vue_init.js', 'public/js')
  .sass('resources/assets/sass/common.scss', 'public/css')
  .sass(
    'resources/assets/admin/sass/font.scss',
    'public/assets/admin/css/font.css'
  )
  .sass(
    'resources/assets/admin/sass/app.scss',
    'public/assets/admin/css/app.css'
  )
  .styles(
    [
      'resources/assets/admin/css/theme/bootstrap.min.css',
      'resources/assets/admin/css/theme/components.min.css',
      'resources/assets/admin/css/theme/todo-2.min.css',
      'node_modules/select2/dist/css/select2.min.css',
      'node_modules/select2-bootstrap-theme/dist/select2-bootstrap.min.css',
      'resources/assets/admin/css/theme/plugins.min.css',
      'resources/assets/admin/css/theme/layout.min.css',
      'resources/assets/admin/css/theme/darkblue.min.css',
    ],
    'public/assets/admin/css/theme.css'
  )
  .babel(
    [
      'resources/assets/admin/js/theme/jquery.min.js',
      'resources/assets/admin/js/theme/bootstrap.min.js',
      'resources/assets/admin/js/theme/js.cookie.min.js',
      'resources/assets/admin/js/theme/jquery.slimscroll.min.js',
      'resources/assets/admin/js/theme/app.min.js',
      'resources/assets/admin/js/theme/layout.min.js',
    ],
    'public/assets/admin/js/theme.js'
  )
  .copyDirectory('resources/assets/admin/images', 'public/assets/admin/images')
  .copyDirectory('resources/assets/admin/fonts', 'public/assets/admin/fonts')
  .copyDirectory('resources/assets/js/push-notification', 'public/js/push-notification')
// .styles(
//     "resources/assets/admin/css/datatables/datatables.min.css",
//     "public/assets/admin/css/datatables.css"
// )
// .babel(
//     [
//         "resources/assets/admin/js/datatables/datatables.min.js",
//         "resources/assets/admin/js/datatables/datatables.bootstrap.js"
//     ],
//     "public/assets/admin/js/datatables.js"
// )
  .js(
    'resources/assets/admin/js/swal/swal.js',
    'public/assets/admin/js/swal.js'
  )
  .js(
    'resources/assets/admin/js/users/list.js',
    'public/assets/admin/js/users/list.js'
  )
  .js(
    'resources/assets/js/jquery.knob.js',
    'public/js/jquery.knob.js'
  )
  .styles(
    [
      'resources/assets/root/css/theme/bootstrap.min.css',
      'resources/assets/root/css/theme/components.min.css',
      'node_modules/select2/dist/css/select2.min.css',
      'node_modules/select2-bootstrap-theme/dist/select2-bootstrap.min.css',
      'resources/assets/admin/css/theme/plugins.min.css',
      'resources/assets/root/css/theme/layout.min.css',
      'resources/assets/root/css/theme/default.min.css',
      'resources/assets/root/css/theme/custom.min.css',
      'resources/assets/root/css/theme/bootstrap-fileinput.css'
    ],
    'public/assets/root/css/theme.css'
  )
  .babel(
    [
      'resources/assets/root/js/theme/jquery.min.js',
      'resources/assets/root/js/theme/bootstrap.min.js',
      'resources/assets/root/js/theme/js.cookie.min.js',
      'resources/assets/root/js/theme/app.min.js',
      'resources/assets/root/js/theme/layout.min.js',
      'resources/assets/root/js/theme/bootstrap-fileinput.js',
      'resources/assets/root/js/theme/active-menu.js',
    ],
    'public/assets/root/js/theme.js'
  )
  .copyDirectory('resources/assets/root/images', 'public/assets/root/images')
  .styles(
    [
      'resources/assets/admin/css/theme/login.min.css',
    ],
    'public/assets/admin/css/login.min.css'
  )
  .js(
    'resources/assets/admin/js/theme/login.min.js',
    'public/assets/admin/js/login.min.js'
  );

let fs = require('fs');


let getFiles = function (dir) {
  // get all 'files' in this directory
  // filter directories
  return fs.readdirSync(dir).filter(file => {
    return fs.statSync(`${dir}/${file}`).isFile();
  });
};

getFiles('resources/assets/js/modules').forEach(function (filepath) {
  mix.js('resources/assets/js/modules/' + filepath, 'js/modules');
});
