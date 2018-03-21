<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 15:20
 */


use yii\helpers\ArrayHelper;


$settings = require(__DIR__ . '/settings.php');

return [
    'id'      => 'yatt',
    'name'    => 'Yatt',
    'version' => '0.2a',

    'bootstrap' => ['log'],
    'language'  => 'ru-RU',

    'components' => [
        'db'          => ArrayHelper::merge(
            [
                'class' => 'yii\db\Connection',
                'enableSchemaCache' => true,
            ],
            $settings['db']
        ),
        'cache'       => ArrayHelper::merge(
            [
                'class' => YII_DEBUG ? 'yii\caching\FileCache' : 'yii\caching\DummyCache',
            ],
            $settings['cache']
        ),
        'i18n'        => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
        'authManager' => [
            'class' => 'app\components\AuthProjectManager',
            'cache' => 'cache',
        ],
    ],
    'container'  => [
        'definitions' => [
            /* text Editors */
            'plainEditor'              => [
                'class' => 'app\components\textEditors\PlainEditor',
            ],
            'wysiwygEditor'            => [
                'class'         => 'dosamigos\ckeditor\CKEditor',
                'preset'        => 'custom',
                'clientOptions' => [
                    'height'        => 400,
                    'toolbarGroups' => [// здесь вырезаны кнопки цвета, изображений, поскольку нет соответствующих плагинов.
                        ['name' => 'document', 'groups' => ['mode', 'document', 'doctools']],
                        ['name' => 'clipboard', 'groups' => ['clipboard', 'undo']],
                        ['name' => 'links', 'groups' => ['links']],
                        ['name' => 'insert', 'groups' => ['insert']],
                        ['name' => 'about', 'groups' => ['about']],
                        '/',
                        ['name' => 'others', 'groups' => ['others']],
                        ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
                        ['name' => 'paragraph', 'groups' => ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']],
                        ['name' => 'styles', 'groups' => ['styles']],
                        ['name' => 'editing', 'groups' => ['find', 'selection', 'editing']],
                    ],
                    'removeButtons' => 'Scayt,Anchor,Image,Styles',
                ],
            ],
            'mdEditor'                 => [
                'class' => 'app\components\textEditors\MdEditor',
            ],
            /* text Renderers */
            'plainRenderer'            => [
                'class' => 'app\components\textRenderers\PlainRenderer',
            ],
            'wysiwygRenderer'          => [
                'class' => 'app\components\textRenderers\WysiwygRenderer',
            ],
            'mdRenderer'               => [
                'class'  => 'app\components\textRenderers\MdRenderer',
                // на мой взгляд самый адекватный диалект. Отрабатывает переносы и довольно либерален к оформлению пунктов
                'flavor' => 'gfm-comment',
            ],
            /* переопределения классов Yii */
            'yii\bootstrap\ActiveForm' => [
                'fieldClass' => 'app\widgets\TextEditorField',
            ],
        ],
        'singletons'  => [

        ],
    ],
    'params'     => [
        'defaultEditor' => 'plain',
        'editorList'    => ['plain', 'wysiwyg', 'md'],
    ],
];