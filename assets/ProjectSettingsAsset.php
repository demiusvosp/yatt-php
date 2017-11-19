<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ProjectSettingsAsset extends AssetBundle
{
    //public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $sourcePath = '@app/assets/res';

    public $css = [
        'css/projectSettings.css',
    ];
    public $js = [
        'js/dictedit.js',
        'js/useraccesses.js',
    ];

    public $depends = [
        'app\assets\AppAsset',
        'kartik\select2\Select2Asset',
    ];
}
