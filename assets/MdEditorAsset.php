<?php
/**
 * Lepture Markdown Editor assets class file
 *
 * @author Evgeniy Kuzminov
 * @license MIT License
 * http://stdout.in
 */

namespace app\assets;

use yii\web\AssetBundle;


class MdEditorAsset extends AssetBundle
{
    //public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $sourcePath = '@app/assets/res/mdeditor';

    public $js = [
        'js/marked.js',
        'js/editor.js',
    ];
    public $css = [
        'css/editor.css',
        'fonts/icomoon.woff',
        'fonts/icomoon.ttf',
        'fonts/icomoon.svg',
        'fonts/icomoon.eot',
        'fonts/icomoon.dev.svg',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}