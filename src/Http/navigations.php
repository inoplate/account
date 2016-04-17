<?php

return [
    'main' => [
        [
            'id' => 'admin.dashboard.all',
            'label' => trans('inoplate-account::labels.dashboard.title'),
            'hookable' => true,
            'url' => '',
            'order' => 0,
            'attributes' => [
                'icon'  => 'fa fa-dashboard'
            ],
            'childs' => [
                [
                    'label' => trans('inoplate-account::labels.main_dashboard.title'),
                    'url' => 'account.admin.dashboard.index.get',
                    'order' => 0,
                    'attributes' => [
                        'icon'  => 'fa fa-circle-o'
                    ],
                ]
            ]
        ],
    ],
    'utility' => [
        [
            'label' => trans('inoplate-account::labels.title'),
            'url' => '',
            'permission' => '',
            'order' => 1,
            'attributes' => [
                'icon'  => 'fa fa-get-pocket'
            ],
            'childs' => [
                [
                    'label' => trans('inoplate-account::labels.users.title'),
                    'url' => 'account.admin.users.index.get',
                    'permission' => 'account.admin.users.index.get',
                    'attributes' => [
                        'icon'  => 'fa fa-users'
                    ],      
                ],
                [
                    'label' => trans('inoplate-account::labels.roles.title'),
                    'url' => 'account.admin.roles.index.get',
                    'permission' => 'account.admin.roles.index.get',
                    'attributes' => [
                        'icon'  => 'fa fa-tags'
                    ],      
                ],
                [
                    'label' => trans('inoplate-account::labels.permissions.title'),
                    'url' => 'account.admin.permissions.index.get',
                    'permission' => 'account.admin.permissions.index.get',
                    'attributes' => [
                        'icon'  => 'fa fa-unlock-alt'
                    ],      
                ],
            ]
        ],
        [
            'label' => trans('inoplate-account::labels.profile.title'),
            'url' => 'account.admin.profile.index.get',
            'order' => 100, // Take it to the bottom
            'attributes' => [
                'icon'  => 'fa fa-bookmark'
            ],      
        ],
    ]
];