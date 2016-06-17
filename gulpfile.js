var elixir = require('laravel-elixir');

var datatablesScripts = [
    'public/vendor/inoplate-adminutes/vendor/datatables/js/jquery.dataTables.min.js',
    'public/vendor/inoplate-adminutes/vendor/datatables/js/dataTables.bootstrap.min.js',
    'public/vendor/inoplate-adminutes/vendor/datatables/extensions/buttons/js/dataTables.buttons.min.js',
    'public/vendor/inoplate-adminutes/vendor/datatables/extensions/select/js/dataTables.select.min.js',
    'public/vendor/inoplate-foundation/js/datatables.extended.js'
];

elixir(function(mix){
    mix.less('auth/register.less', 'public/auth')
       .coffee('users/register.coffee', 'public/users')
       .coffee('users/index.coffee', 'public/users')
       .coffee('role/create.coffee', 'public/role')
       .coffee('role/index.coffee', 'public/role')
       .coffee('permissions/index.coffee', 'public/permissions')
       .coffee('widgets/user-card.coffee', 'public/widgets/user-card');
})