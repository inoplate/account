var elixir = require('laravel-elixir');

elixir(function(mix){
    mix.less('auth/register.less', 'public/auth')
       .coffee('users/register.coffee', 'public/users')
       .coffee('users/index.coffee', 'public/users')
       .coffee('role/create.coffee', 'public/role')
       .coffee('role/index.coffee', 'public/role')
       .coffee('permissions/index.coffee', 'public/permissions');
})