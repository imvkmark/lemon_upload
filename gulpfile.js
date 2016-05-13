/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

var elixir = require('laravel-elixir');
var gulp = require('gulp');
var sass = require('gulp-ruby-sass');
var compass = require('gulp-compass');

/*var exec = require('child_process').exec;
var cmdStr = 'apidoc -i app/Http/Controllers/Api/ -o resources/docs/api';
exec(cmdStr, function(err,stdout,stderr){
    if(err) {
        console.log('Generate Api Doc Error:'+stderr);
    } else {
        console.log(stdout);
    }
});*/

// copy font

elixir(function (mix) {
	var directories = {
		'resources/assets/fonts' : 'public/css/fonts',
		'resources/assets/css_images' : 'public/css/images',
		'resources/docs/api' : 'public/docs/api'
	};
	for (var dir in directories) {
		mix.copy(dir, directories[dir]);
	}
});

//sass 生成 css
gulp.task('compass', function () {
 gulp.src('resources/assets/sass/!**.scss')
     .pipe(compass({
      config_file : 'resources/assets/sass/config.rb',
      css : 'public/css/',
      sass : 'resources/assets/sass/'
     }));
});
gulp.task('default', function () {
 gulp.start('compass');
 gulp.start('copy');
});

/*
 elixir.config.sourcemaps = false; //.map文件(debugging)
 elixir.config.assetsPath = 'public/css';
 elixir(function(mix) {
 mix.sass([
 "lemon/seajs.scss"
 ],'public/css/seajs.css');
 });
 */

/*!
 * gulp
 * $ npm install gulp-ruby-sass gulp-autoprefixer gulp-minify-css gulp-jshint gulp-concat gulp-uglify gulp-imagemin gulp-notify gulp-rename gulp-livereload gulp-cache del --save-dev
 */
// Load plugins

/*
 gulp.task('styles', function () {
 return sass('public/css/sass/lemon/seajs.scss')
 .pipe(gulp.dest('public/css'));
 });
 gulp.task('default', function () {
 gulp.start('compass');
 });
 gulp.task('compass', function () {
 gulp.src('public/css/sass/lemon/seajs.scss')
 .pipe(compass({
 config_file : 'public/css/sass/config.rb',
 css : 'public/css/lemon',
 sass : 'public/css/sass/lemon'
 }))
 });
 gulp.task('watch', function () {
 gulp.watch('public/css/sass/lemon/seajs.scss', ['compass']);
 });
 */
