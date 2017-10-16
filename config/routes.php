<?php

return [
    // authentication
    'auth/login'         => 'authentication/login',
    'auth/logout'        => 'authentication/logout',
    'auth/registration'  => 'authentication/registration',
    'auth/email-confirm' => 'authentication/confirm-email',
    'auth/profile'       => 'authentication/profile',

    // admin

    // project managment
    'admin/project/list'     => 'admin/project/index',
    'admin/project/create'   => 'admin/project/create',
    'admin/project/<id:\d+>/edit'     => 'admin/project/update',// внутренний маршрут не стоит того, чтобы переопределять кнопку в gridview
    'admin/project/<id:\d+>/view'     => 'admin/project/view',
    'admin/project/<id:\d+>/delete'   => 'admin/project/delete',

    // project
    'p/<suffix:\w+>' => 'project/overview',

    // project setting
    'p/<suffix:\w+>/setting/main'  =>  'project-settings/main',
    'p/<suffix:\w+>/setting/stages' => 'project-settings/stages',
    'p/<suffix:\w+>/setting/types'  => 'project-settings/types',
    'p/<suffix:\w+>/setting/versions' => 'project-settings/versions',

    // события проекта
    // Справочники
    'DELETE p/<suffix:\w+>/dict/delete-item' => 'dict/delete-item',

    // task
    'p/<suffix:\w+>/task/list'   => 'task/list',
    'p/<suffix:\w+>/task/create' => 'task/create',
    'p/<suffix:\w+>/task/<index:\d+>/edit' => 'task/edit',
    'p/<suffix:\w+>/task/<index:\d+>'      => 'task/view',

    // main
    'captcha' => 'main/captcha',
    'error'   => 'main/error',
    'about'   => 'main/about',
    '/' => 'main/index',
];
