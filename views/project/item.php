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
<div class="project-item-block">
    <a href="<?=Url::to(['project/overview', 'suffix' => $project->suffix])?>"><h3><?=$project->name ?></h3></a>
    <p>Здесь будет задачи, статистика и проч...</p>
</div>
