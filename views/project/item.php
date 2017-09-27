<?php
/**
 * Блок проекта. используется в dashboard'ах
 * User: demius
 * Date: 24.09.17
 * Time: 15:51
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\entities\Project;

/* @var $this yii\web\View */
/* @var $project Project */

?>
<div class="box box-solid box-success project-item-block"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <a href="<?=Url::to(['project/overview', 'suffix' => $project->suffix])?>">
            <h3 class="box-title">
                <?=$project->name ?>
            </h3>
        </a>
    </div>
    <div class="box-body">
        <p>Здесь будет задачи, статистика и проч...</p>
    </div>
</div>
