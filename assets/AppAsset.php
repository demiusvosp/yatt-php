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
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    // пока оставим на месте, потом, в рамках #181, когда заюзаем лесс и минификатор будем их копировать и собирать на лету.
    //public $sourcePath = '@app/assets/res';

    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/dictedit.js',
        'js/closetask.js',
        'js/useraccesses.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\jui\JuiAsset',
        'dmstr\web\AdminLteAsset',
        'kartik\select2\Select2Asset',
    ];
}
