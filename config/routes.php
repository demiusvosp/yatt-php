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
    'pm/list'     => 'project-manager/index',
    'pm/create'   => 'project-manager/create',
    'pm/<id:\d+>/edit'     => 'project-manager/update',// внутренний маршрут не стоит того, чтобы переопределять кнопку в gridview
    'pm/<id:\d+>/view'     => 'project-manager/view',
    'pm/<id:\d+>/delete'   => 'project-manager/delete',

    // project
    'p/<suffix:\w+>'  => 'project/overview', // обзор проекта
    // план проекта
    // события проекта
    // создать задачу  (может это уже task??

    // main
    'captcha' => 'main/captcha',
    'error'   => 'main/error',
    'about'   => 'main/about',
    '/' => 'main/index',
];
