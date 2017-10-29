<?php

$params = require(__DIR__ . '/params.php');
$config = require(__DIR__ . '/main.php');

$config = array_merge_recursive($config, [
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'main/index',
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
            'class' => 'app\components\User',
            'identityClass' => 'app\models\entities\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['auth/login']
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require(__DIR__ . '/routes.php'),
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'debug' => YII_DEBUG,
                        'auto_reload' => YII_DEBUG,
                        'strict_variables' => YII_DEBUG,
                    ],
                    'globals' => [
                        'url'  => ['class' => 'app\helpers\ProjectUrl'],
                        'html' => ['class' => '\yii\helpers\Html']
                    ],
                    'functions' => [
                        't' => 'Yii::t',
                    ],
                    'uses' => ['yii\bootstrap'],
//                    'extensions' => [
//                        \yii\twig\html\HtmlHelperExtension::class,
//                    ],
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
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\AdminModule',
        ],
    ],
    'params' => $params,
]);

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
    $config['components']['view']['renderers']['twig']['extensions'][] = [
        'class' => 'Twig\Extension\DebugExtension'
    ];
    // лог для отладки (пок ане получилось заставить работать, позе к ним вернемся)
    $config['components']['log']['targets'][] = [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning', 'info', 'trace'],
        'categories' => ['debug*', 'access'],
        'logVars' => [],
        'exportInterval' => 1,
    ];
}

return $config;
