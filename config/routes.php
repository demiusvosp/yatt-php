<?php

return [
    // authentication
    'auth/login'                             => 'authentication/login',
    'auth/logout'                            => 'authentication/logout',
    'auth/registration'                      => 'authentication/registration',
    'auth/email-confirm'                     => 'authentication/confirm-email',

    // project
    'p/<suffix:\w+>'                         => 'project/overview',

    // task
    'task/<suffix:\w+>/list'                 => 'task/list',
    'task/<suffix:\w+>/open'                 => 'task/open',
    'task/<suffix:\w+>-<index:\d+>'          => 'task/view',
    'task/<suffix:\w+>-<index:\d+>/edit'     => 'task/edit',
    'task/<suffix:\w+>-<index:\d+>/change-stage' => 'task/change-stage',
    'task/<suffix:\w+>-<index:\d+>/close'    => 'task/close',

    // project setting
    'p/<suffix:\w+>/setting/<action:\w+>'    => 'project-settings/<action>',

    // события проекта
    // Справочники
    'p/<suffix:\w+>/dict/<action:(delete|past)>' => 'dict/<action>',

    // admin

    // project managment
    'admin/project/list'                     => 'admin/project/index',
    'admin/project/create'                   => 'admin/project/create',
    'admin/project/<id:\d+>/edit'            => 'admin/project/update',
    // внутренний маршрут не стоит того, чтобы переопределять кнопку в gridview (во всяком случае пока)
    'admin/project/<id:\d+>/view'            => 'admin/project/view',
    'admin/project/<id:\d+>/delete'          => 'admin/project/delete',

    // user managment
    'admin/user/list'                        => 'admin/user/index',
    'admin/user/create'                      => 'admin/user/create',
    'admin/user/<id:\d+>/edit'               => 'admin/user/update',
    'admin/user/<id:\d+>/view'               => 'admin/user/view',
    'admin/user/<id:\d+>/delete'             => 'admin/user/delete',

    // user managment
    'admin/access/list'                      => 'admin/access/index',
    // access ajax
    'POST access/assign'                     => 'access/assign-role',
    'POST access/revoke'                     => 'access/revoke-role',

    // user
    'user/profile'                           => 'user/profile',
    'user/find-for-choose'                   => 'user/find-for-choose',

    // main
    'captcha'                                => 'main/captcha',
    'error'                                  => 'main/error',
    'about'                                  => 'main/about',
    '/'                                      => 'main/index',
];
