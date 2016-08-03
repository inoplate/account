<?php

// Public routes
// This endpoints can be accessed by anyone

$router->group(['middleware' => ['guest']], function($router){
    // Login routes

    $router->get('login',['uses' => 'AuthController@getLogin', 'as' => 'account.auth.login.get']);
    $router->post('login', ['uses' => 'AuthController@postLogin', 'as' => 'account.auth.login.post']);

    // User registration link request routes
    
    $router->get('register',['uses' => 'AuthController@getRegister', 'as' => 'account.auth.register.get']);
    $router->post('register', ['uses' => 'AuthController@postRegister', 'as' => 'account.auth.register.post']);

    // User confirmation link request routes
    
    // $router->get('register/confirmation/{token}', ['uses' => 'RegisterController@getConfirmation', 'as' => 'account.auth.register.confirmation.get']);

    // Password reset link request routes
    
    $router->get('password/email', ['uses' => 'PasswordController@getEmail', 'as' => 'account.password.email.get']);
    $router->post('password/email', ['uses' => 'PasswordController@postEmail', 'as' => 'account.password.email.post']);

    // Password reset routes
    
    $router->get('password/reset/{token?}', ['uses' => 'PasswordController@getReset', 'as' => 'account.password.reset.get']);
    $router->post('password/reset', ['uses' => 'PasswordController@postReset', 'as' => 'account.password.reset.post']);
});

// Logout routes

$router->get('logout', ['uses' => 'AuthController@getLogout', 'as' => 'account.auth.logout.get']);
$router->get('confirm-email-change/{token}', ['uses' => 'ConfirmEmailChangeController@putConfirm']);

// Protected routes
// Only authenticated and authorized user can access this endpoints

$router->group(['prefix' => 'admin', 'middleware' => ['auth']], function($router){
    $router->get('dashboard', ['uses' => 'DashboardController@getIndex', 'as' => 'account.admin.dashboard.index.get']);

    $router->group(['middleware' => ['authorize']], function($router) {
        $router->get('inoplate-account/users', ['uses' => 'UsersController@getIndex', 'as' => 'account.admin.users.index.get']);
        $router->get('inoplate-account/users/datatables/{trashed?}', ['uses' => 'UsersController@getDatatables', 'as' => 'account.admin.users.datatables.get']);
        
        $router->get('inoplate-account/users/register', ['uses' => 'UsersController@getRegister', 'as' => 'account.admin.users.register.get']);
        $router->post('inoplate-account/users/register', ['uses' => 'UsersController@postRegister', 'as' => 'account.admin.users.register.post']);

        $router->get('inoplate-account/users/{id}', ['uses' => 'UsersController@getShow', 'as' => 'account.admin.users.show.get']);

        $router->get('inoplate-account/users/{id}/edit', ['uses' => 'UsersController@getUpdate', 'as' => 'account.admin.users.update.get']);
        $router->put('inoplate-account/users/{id}/edit', ['uses' => 'UsersController@putUpdate', 'as' => 'account.admin.users.update.put']);
        
        $router->delete('inoplate-account/users/{ids}', ['uses' => 'UsersController@delete', 'as' => 'account.admin.users.delete']);
        $router->put('inoplate-account/users/restore/{ids}', ['uses' => 'UsersController@putRestore', 'as' => 'account.admin.users.restore.put']);
        $router->delete('inoplate-account/users/delete/{ids}', ['uses' => 'UsersController@deleteForceDelete', 'as' => 'account.admin.users.force-delete']);

        $router->get('inoplate-account/roles', ['uses' => 'RoleController@getIndex', 'as' => 'account.admin.roles.index.get']);
        $router->get('inoplate-account/roles/datatables/{trashed?}', ['uses' => 'RoleController@getDatatables', 'as' => 'account.admin.roles.datatables.get']);

        $router->get('inoplate-account/roles/create', ['uses' => 'RoleController@getCreate', 'as' => 'account.admin.roles.create.get']);
        $router->post('inoplate-account/roles/create', ['uses' => 'RoleController@postCreate', 'as' => 'account.admin.roles.create.post']);

        $router->get('inoplate-account/roles/{id}/edit', ['uses' => 'RoleController@getUpdate', 'as' => 'account.admin.roles.update.get']);
        $router->put('inoplate-account/roles/{id}/edit', ['uses' => 'RoleController@putUpdate', 'as' => 'account.admin.roles.update.put']);

        $router->delete('inoplate-account/roles/{ids}', ['uses' => 'RoleController@delete', 'as' => 'account.admin.roles.delete']);
        $router->put('inoplate-account/roles/restore/{ids}', ['uses' => 'RoleController@putRestore', 'as' => 'account.admin.roles.restore.put']);
        $router->delete('inoplate-account/roles/delete/{ids}', ['uses' => 'RoleController@deleteForceDelete', 'as' => 'account.admin.roles.force-delete']);

        $router->get('inoplate-account/permission', ['uses' => 'PermissionController@getIndex', 'as' => 'account.admin.permissions.index.get']);
        $router->put('inoplate-account/permission/{roleId}/{permissionId}', ['uses' => 'PermissionController@putUpdate', 'as' => 'account.admin.permissions.update.put']);
    });

    $router->get('profile', ['uses' => 'ProfileController@getIndex', 'as' => 'account.admin.profile.index.get']);
    $router->put('profile', ['uses' => 'ProfileController@putUpdate', 'as' => 'account.admin.profile.index.put']);    
    $router->put('profile/avatar/{id?}', ['uses' => 'ProfileController@putUpdateAvatar', 'as' => 'account.admin.profile.avatar.put']);
});