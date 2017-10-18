<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 27.09.17
 * Time: 0:46
 */
use yii\helpers\Html;

use app\models\entities\Task;

/* @var $this yii\web\View */
/* @var $task Task */

$this->title = Yii::t('task', 'Task: ') . $task->getName();
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row-fluid task-toolbar">
    <div class="btn-group">
        <?=Html::a(
            Yii::t('task', 'Edit task'),// '<span class="fa fa-edit"></span>' слишком высокая, такую лучше перенести куда-то
            ['task/edit', 'suffix' => Yii::$app->projectService->getSuffixUrl(), 'index' => $task->index],// с этими строками в task надо что-то делать
            ['class' => 'btn btn-primary']
        );?>
    </div>
</div>
<div class="row-fluid">
    <div class="col-md-3 task-dict-block">
        <p>
            Этап: <b><?=$task->stage->name ?></b>
        </p>
        <p>
            Прогресс
        </p>
        <p>
            Тип задачи: <b><?=$task->type->name ?></b>
        </p>
        <p>
            Категория/подсистема
        </p>
        <p>
            Назначена: <b><?=$task->assigned ? $task->assigned->username : Yii::t('common', 'Not set') ?></b><br>
        </p>
        <p>
            Приоритет: <b><?=$task->getPriorityName()?></b>
        </p>
        <p>
            Трудоемкость: <b><?=$task->difficulty->name ?></b>
        </p>
        <p>
            Обнаруженна в версии: <b><?=$task->versionOpen ? $task->versionOpen->name : Yii::t('common', 'Not set')  ?></b>
        </p>
        <p>
            Дата обнаружения: <b><?= Yii::$app->formatter->asDate($task->created_at) ?></b>
        </p>
        <p>
            Ожидается в версии: <b><?=$task->versionClose ? $task->versionClose->name : Yii::t('common', 'Not set') ?></b>
        </p>
    </div>

    <div class="col-md-9 task-text-block">
        <h2><?=$task->getName()?> - <?=$task->caption?></h2>
        <div class="well">
            <?=$task->description ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>
