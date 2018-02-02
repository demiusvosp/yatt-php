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
    'container'  => [
        'definitions' => [
            /* text Editors */
            'plainEditor' => [
                'class' => 'app\components\textEditors\PlainEditor',
            ],
            'wysiwygEditor' => [
                'class' => 'app\components\textEditors\WysiwygEditor',
            ],
            'mdEditor' => [
                'class' => 'app\components\textEditors\MdEditor',
            ],
            /* text Renderers */
            'plainRenderer' => [
                'class' => 'app\components\textRenderers\PlainRenderer',
            ],
            'wysiwygRenderer' => [
                'class' => 'app\components\textRenderers\WysiwygRenderer',
            ],
            'mdRenderer' => [
                'class' => 'app\components\textRenderers\MdRenderer',
            ],
            /* переопределения классов Yii */
            'yii\bootstrap\ActiveForm' => [
                'fieldClass' => 'app\widgets\TextEditorField'
            ],
        ],
        'singletons'  => [

        ],
    ],
    'params' => [
        'defaultEditor' => 'plain',
        'editorList' => ['plain', 'wysiwyg', 'md'],
    ],
];