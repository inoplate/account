<?php

return [
    'account.admin.users.index.get' => [
        'account.admin.users.datatables.get',
        'account.admin.users.datatables-trashed.get',
    ],
    'account.admin.users.update.get' => [
        'account.admin.users.update.put',
        'account.admin.users.ban.put',
        'account.admin.users.activate.put',
        'account.admin.users.grant-role.put',
        'account.admin.users.revoke-role.put',
    ],
    'account.admin.users.register.get' => [
        'account.admin.users.register.post',
        'account.admin.users.restore.put',
    ],
    'account.admin.users.delete' => [
        'account.admin.users.force-delete',
    ],

    'account.admin.roles.index.get' => [
        'account.admin.roles.datatables.get',
        'account.admin.roles.datatables-trashed.get',
    ],

    'account.admin.roles.create.get' => [
        'account.admin.roles.create.post',
        'account.admin.roles.restore.put',
    ],

    'account.admin.roles.update.get' => [
        'account.admin.roles.update.put',
    ],

    'account.admin.roles.delete' => [
        'account.admin.roles.force-delete',
    ],
];  