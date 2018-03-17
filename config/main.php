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
                'class' => 'dosamigos\ckeditor\CKEditor',
                'preset' => 'custom',
                'clientOptions' => [
                    'height' => 400,
                    'toolbarGroups' => [// здесь вырезаны кнопки цвета, изображений, поскольку нет соответствующих плагинов.
                        ['name' => 'document', 'groups' => ['mode', 'document', 'doctools']],
                        ['name' => 'clipboard', 'groups' => ['clipboard', 'undo']],
                        ['name' => 'links', 'groups' => ['links']],
                        ['name' => 'insert', 'groups' => ['insert']],
                        ['name' => 'about', 'groups' => ['about']],
                        '/',
                        ['name' => 'others', 'groups' => ['others']],
                        ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
                        ['name' => 'paragraph', 'groups' => [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']],
                        ['name' => 'styles', 'groups' => ['styles']],
                        ['name' => 'editing', 'groups' => ['find', 'selection', 'editing']],
                    ],
                    'removeButtons' => 'Scayt,Anchor,Image,Styles',
                ],
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
                // на мой взгляд самый адекватный диалект. Отрабатывает переносы и довольно либерален к оформлению пунктов
                'flavor' => 'gfm-comment',
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