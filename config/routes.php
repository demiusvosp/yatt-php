<?php

return [
    // authentication
    'auth/login'                             => 'authentication/login',
    'auth/logout'                            => 'authentication/logout',
    'auth/registration'                      => 'authentication/registration',
    'auth/email-confirm'                     => 'authentication/confirm-email',
    'auth/profile'                           => 'authentication/profile',

    // project
    'p/<suffix:\w+>'                         => 'project/overview',

    // project setting
    'p/<suffix:\w+>/setting'                 => 'project-settings/main',
    'p/<suffix:\w+>/setting/stages'          => 'project-settings/stages',
    'p/<suffix:\w+>/setting/types'           => 'project-settings/types',
    'p/<suffix:\w+>/setting/categories'      => 'project-settings/categories',
    'p/<suffix:\w+>/setting/versions'        => 'project-settings/versions',
    'p/<suffix:\w+>/setting/difficulty'      => 'project-settings/difficulties',

    // события проекта
    // Справочники
    'DELETE p/<suffix:\w+>/dict/delete-item' => 'dict/delete-item',

    // task
    'task/<suffix:\w+>/list'                 => 'task/list',
    'task/<suffix:\w+>/create'               => 'task/create',
    'task/<suffix:\w+>-<index:\d+>'          => 'task/view',
    'task/<suffix:\w+>-<index:\d+>/edit'     => 'task/edit',
    'task/<suffix:\w+>-<index:\d+>/close'    => 'task/close',

    // admin

    // project managment
    'admin/project/list'                     => 'admin/project/index',
    'admin/project/create'                   => 'admin/project/create',
    'admin/project/<id:\d+>/edit'            => 'admin/project/update',
    // внутренний маршрут не стоит того, чтобы переопределять кнопку в gridview (во всяком случае пока)
    'admin/project/<id:\d+>/view'            => 'admin/project/view',
    'admin/project/<id:\d+>/delete'          => 'admin/project/delete',

    // user managment
    'admin/user/list'                     => 'admin/user/index',
    'admin/user/create'                   => 'admin/user/create',
    'admin/user/<id:\d+>/edit'            => 'admin/user/update',
    'admin/user/<id:\d+>/view'            => 'admin/user/view',
    'admin/user/<id:\d+>/delete'          => 'admin/user/delete',


    // main
    'captcha'                                => 'main/captcha',
    'error'                                  => 'main/error',
    'about'                                  => 'main/about',
    '/'                                      => 'main/index',
];
