<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'yatt',
    'name' => 'Yatt',
    'version' => '0.1a',

    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'main/index',
    'bootstrap' => ['log'],
    'language' => 'ru_RU',

    'layout' => 'default',

    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'uRXZ9FM2KwLW1e_a87yJ57G9LoDQUYJ8',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\entities\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'main/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require(__DIR__ . '/routes.php'),
        ],
        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => [
                        'Yii'  => ['class' => '\Yii'],
                        'html' => ['class' => '\yii\helpers\Html'],
                        'url'  => ['class' => 'app\helpers\ProjectUrl'],
                    ],
                    'functions' => [
                        't' => 'Yii::t',
                    ],
                    'uses' => ['yii\bootstrap'],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app\translations', немного логично, но не факт, что настолько, чтобы уходить от унификации
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-green',
                ],
            ],
        ],
        // сервис поддержки проектов для полномочий, вида треккера, меню и прочее.
        //   его можно было бы назвать по аналогии с сервисом yii\web\user просто project (но мне претит два одноименных разных класса, которые будут юзать всюду и вместе)
        'projectService' => [
            'class' => 'app\components\ProjectService',
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\AdminModule',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    // лог для отладки (пок ане получилось заставить работать, позе к ним вернемся)
    $config['components']['log']['targets'][] = [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning', 'info', 'trace'],
        'categories' => ['debug*'],
        'logVars' => [],
        'exportInterval' => 1,
    ];
}

return $config;
