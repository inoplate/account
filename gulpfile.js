var gulp = require("gulp");
var elixir = require('laravel-elixir');
var shell = require('gulp-shell');
var task = elixir.Task;


elixir.extend('publishAssets', function() {
    new task('publishAssets', function() {
        return gulp.src("").pipe(shell("cd ../../../ && php artisan vendor:publish --provider=\"Inoplate\\Account\\Providers\\AccountServiceProvider\" --tag=public --force"));
    }).watch("resources/assets/**");
});

elixir(function(mix){
    mix.less('auth/register.less', 'public/auth')
       .coffee('users/register.coffee', 'public/users')
       .coffee('users/index.coffee', 'public/users')
       .coffee('role/create.coffee', 'public/role')
       .coffee('role/index.coffee', 'public/role')
       .coffee('permissions/index.coffee', 'public/permissions')
       .coffee('widgets/user-card.coffee', 'public/widgets/user-card')
       .publishAssets();
})