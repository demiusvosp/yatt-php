<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 15:20
 */

return [
    'id'      => 'yatt',
    'name'    => 'Yatt',
    'version' => '0.2a',

    'bootstrap' => ['log'],
    'language'  => 'ru-RU',

    'container'  => [
        'definitions' => [

        ],
        'singletons'  => [

        ],
    ],
    'components' => [
        'db'             => require(__DIR__ . '/db.php'),
        'i18n'           => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app\translations', немного логично, но не факт, что настолько, чтобы уходить от унификации
                ],
            ],
        ],
        'projectService' => [
            'class' => 'app\components\ProjectService',
        ],
        'authManager' => [
            'class' => 'app\components\AccessManager',
        ],
    ],
];