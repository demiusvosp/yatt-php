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
class AppAsset extends AssetBundle
{
    //public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $sourcePath = '@app/assets/res';

    public $css = [
        'css/common.css',
        'css/project.css',
        'css/task.css',
        'css/comment.css',
    ];
    public $js = [
        'js/closetask.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\jui\JuiAsset',
        'dmstr\web\AdminLteAsset',
    ];
}
