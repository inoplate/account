<?php

return [
    // User permissions

    'account.admin.users.index.get' => 'inoplate-account::permissions.account_admin_user_index_get',
    'account.admin.users.register.get' => 'inoplate-account::permissions.account_admin_user_register_get',
    'account.admin.users.update.get' => 'inoplate-account::permissions.account_admin_user_update_get',
    'account.admin.users.trash.put' => 'inoplate-account::permissions.account_admin_user_trash_put',
    'account.admin.users.restore.put' => 'inoplate-account::permissions.account_admin_user_restore_put',
    'account.admin.users.delete' => 'inoplate-account::permissions.account_admin_user_delete',

    // Roles permissions
    
    'account.admin.roles.index.get' => 'inoplate-account::permissions.account_admin_role_index_get',
    'account.admin.roles.create.get' => 'inoplate-account::permissions.account_admin_role_create_get',
    'account.admin.roles.update.get' => 'inoplate-account::permissions.account_admin_role_update_get',
    'account.admin.roles.trash.put' => 'inoplate-account::permissions.account_admin_role_trash_put',
    'account.admin.roles.restore.put' => 'inoplate-account::permissions.account_admin_role_restore_put',
    'account.admin.roles.delete' => 'inoplate-account::permissions.account_admin_role_delete',

    // Permission matrices
    
    'account.admin.permissions.index.get' => 'inoplate-account::permissions.account_admin_permission_index_get',
    'account.admin.permissions.update.put' => 'inoplate-account::permissions.account_admin_permission_update_put',
];