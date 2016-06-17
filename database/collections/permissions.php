<?php

return [
    // Users

    [
        'name' => 'account.admin.users.index.get',
        'description' => 'inoplate-account::permissions.account_admin_user_index_get',
        'aliases' => [
            'account.admin.users.datatables.get',
            'account.admin.users.datatables-trashed.get',
        ]
    ],
    [
        'name' => 'account.admin.users.update.get',
        'description' => 'inoplate-account::permissions.account_admin_user_update_get',
        'aliases' => [
            'account.admin.users.update.put',
            'account.admin.users.ban.put',
            'account.admin.users.activate.put',
            'account.admin.users.grant-role.put',
            'account.admin.users.revoke-role.put',
        ]
    ],
    [
        'name' => 'account.admin.users.register.get',
        'description' => 'inoplate-account::permissions.account_admin_user_register_get',
        'aliases' => [
            'account.admin.users.register.post',
            'account.admin.users.restore.put',
        ]
    ],
    [
        'name' => 'account.admin.users.delete',
        'description' => 'inoplate-account::permissions.account_admin_user_delete',
        'aliases' => [
            'account.admin.users.trash.put',
            'account.admin.users.force-delete',
        ]
    ],

    // Roles

    [
        'name' => 'account.admin.roles.index.get',
        'description' => 'inoplate-account::permissions.account_admin_role_index_get',
        'aliases' => [
            'account.admin.roles.datatables.get',
            'account.admin.roles.datatables-trashed.get',
        ]
    ],
    [
        'name' => 'account.admin.roles.create.get',
        'description' => 'inoplate-account::permissions.account_admin_role_create_get',
        'aliases' => [
            'account.admin.roles.create.post',
            'account.admin.roles.restore.put',
        ]
    ],
    [
        'name' => 'account.admin.roles.update.get',
        'description' => 'inoplate-account::permissions.account_admin_role_update_get',
        'aliases' => [
            'account.admin.roles.update.put',
        ]
    ],
    [
        'name' => 'account.admin.roles.delete',
        'description' => 'inoplate-account::permissions.account_admin_role_delete',
        'aliases' => [
            'account.admin.roles.trash.put',
            'account.admin.roles.force-delete',
        ]
    ],

    // Permission matrices
    
    [
        'name' => 'account.admin.permissions.index.get',
        'description' => 'inoplate-account::permissions.account_admin_permission_index_get',
        'aliases' => []
    ],
    [
        'name' => 'account.admin.permissions.update.put',
        'description' => 'inoplate-account::permissions.account_admin_permission_update_put',
        'aliases' => []
    ]
];