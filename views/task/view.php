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
        <div class="row-fluid">
            Этап: <b><?=$task->stage->name ?></b>
        </div>
        <div class="row-fluid">
            Прогресс:
            <div class="progress">
                <div
                    class="progress-bar progress-bar-green" role="progressbar"
                    aria-valuenow="<?=$task->progress?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$task->progress?>%"
                >
                    <span class="sr-only"><?=$task->progress?> Complete</span>
                </div>
                <div class="progress-value"><?=$task->progress?>%</div>
            </div>
        </div>
        <div class="row-fluid">
            Тип задачи: <b><?=$task->type->name ?></b>
        </div>
        <div class="row-fluid">
            Категория: <b><?=$task->category->name ?></b>
        </div>
        <div class="row-fluid">
            Назначена: <b><?=$task->assigned ? $task->assigned->username : Yii::t('common', 'Not set') ?></b><br>
        </div>
        <div class="row-fluid">
            Приоритет: <b><?=$task->getPriorityName()?></b>
        </div>
        <div class="row-fluid">
            Трудоемкость: <b><?=$task->difficulty->name ?></b>
        </div>
        <div class="row-fluid">
            Обнаруженна в версии: <b><?=$task->versionOpen ? $task->versionOpen->name : Yii::t('common', 'Not set')  ?></b>
        </div>
        <div class="row-fluid">
            Дата обнаружения: <b><?= Yii::$app->formatter->asDate($task->created_at) ?></b>
        </div>
        <div class="row-fluid">
            Ожидается в версии: <b><?=$task->versionClose ? $task->versionClose->name : Yii::t('common', 'Not set') ?></b>
        </div>
    </div>

    <div class="col-md-9 task-text-block">
        <h2><?=$task->getName()?> - <?=$task->caption?></h2>
        <div class="well">
            <?=$task->description ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>
