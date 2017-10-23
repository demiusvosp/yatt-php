<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 27.09.17
 * Time: 0:46
 */

use app\helpers\ProjectUrl;
use app\models\entities\Task;
use app\widgets\CloseTaskWidget;

/* @var $this yii\web\View */
/* @var $task Task */

$this->title = Yii::t('task', 'Task: ') . $task->getName();
$this->params['breadcrumbs'][] = $this->title;// как вот это превращать в твиг?
/*
 * полагают так, но я не очень понимаю как это будет работать. Как-нибудь надо будет проверить
 * {{ set(this, 'params', { 'breadcrumbs' : { '' : this.title } }) }}
 */
?>
<div class="row-fluid task-toolbar">
    <div class="btn-group">
        <a href="<?= ProjectUrl::to(['task/edit', 'suffix'=>$task->suffix, 'index'=>$task->index])?>" class="btn btn-app">
            <i class="fa fa-edit"></i>
            <?=Yii::t('task', 'Edit task')?>
        </a>

        <a data-action="<?= ProjectUrl::to(['task/close', 'suffix'=>$task->suffix, 'index'=>$task->index])?>"
           data-toggle="modal" data-target="#closeTask"
           class="btn btn-app"
        >
            <i class="glyphicon glyphicon-ok"></i>
            <?=Yii::t('task', 'Close task')?>
        </a>
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
        <?php if($task->type) { ?>
            <div class="row-fluid">
                Тип задачи: <b><?=$task->type->name ?></b>
            </div>
        <?php } ?>
        <?php if($task->category) { ?>
            <div class="row-fluid">
                Категория: <b><?=$task->category->name ?></b>
            </div>
        <?php } ?>
        <div class="row-fluid">
            Назначена: <b><?=$task->assigned ? $task->assigned->username : Yii::t('common', 'Not set') ?></b><br>
        </div>
        <div class="row-fluid">
            Приоритет: <b><?=$task->getPriorityName()?></b>
        </div>
        <?php if($task->difficulty) { ?>
            <div class="row-fluid">
                Трудоемкость: <b><?=$task->difficulty->name ?></b>
            </div>
        <?php } ?>
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
<?= CloseTaskWidget::widget(['task'=>$task, 'modalId' => 'closeTask']);
?>