// const elixir = require('laravel-elixir');

// require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

// elixir((mix) => {
//     mix.sass('app.scss')
//        .webpack('app.js');
// });


// var elixir = require('laravel-elixir');

// elixir(function(mix) {
//     mix.phpUnit();
// });

var gulp = require('gulp');
var elixir = require('laravel-elixir');

/**
 * 拷贝任何需要的文件
 *
 * Do a 'gulp copyfiles' after bower updates
 */
gulp.task("copyfiles", function() {

    gulp.src("vendor/bower_dl/jquery/dist/jquery.js")
        .pipe(gulp.dest("resources/assets/js/"));

    gulp.src("vendor/bower_dl/bootstrap/less/**")
        .pipe(gulp.dest("resources/assets/less/bootstrap"));

    gulp.src("vendor/bower_dl/bootstrap/dist/js/bootstrap.js")
        .pipe(gulp.dest("resources/assets/js/"));

    gulp.src("vendor/bower_dl/bootstrap/dist/fonts/**")
        .pipe(gulp.dest("public/assets/fonts"));

});

/**
 * Default gulp is to run this elixir stuff
 */
elixir(function(mix) {

    // 合并 scripts
    mix.scripts(['js/jquery.js','js/bootstrap.js'],
        'public/assets/js/admin.js',
        'resources/assets'
    );

    // 编译 Less 
    mix.less('admin.sass', 'public/assets/css/admin.css');
});
