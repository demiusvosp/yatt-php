<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 27.09.17
 * Time: 0:46
 */

use app\helpers\ProjectUrl;
use app\models\entities\Task;
use app\widgets\CloseTask;
use app\widgets\CommentThread;
use app\helpers\ProjectHelper;
use app\helpers\Access;
use app\helpers\HtmlBlock;
use app\helpers\TextEditorHelper;


/* @var $this yii\web\View */
/* @var $task Task */
/* @var $project */


$this->title = HtmlBlock::titleString($task->getFullName(), $project);
$this->params['breadcrumbs'][] = $task->getFullName();

$canClose = !$task->is_closed && Yii::$app->user->can(Access::CLOSE_TASK);
?>
<div class="row-fluid">
    <div class="btn-group">
        <?php if(Yii::$app->user->can(Access::EDIT_TASK)) { ?>
            <a
                class="btn btn-app"
                href="<?= ProjectUrl::to(['task/edit', 'suffix'=>$task->suffix, 'index'=>$task->index])?>"
            >
                <i class="fa fa-edit"></i>
                <?=Yii::t('task', 'Edit task')?>
            </a>
        <?php } ?>

        <?php if($canClose) { ?>
            <button class="btn btn-app"
                data-action="<?= ProjectUrl::to(['task/close', 'suffix'=>$task->suffix, 'index'=>$task->index])?>"
                data-toggle="modal" data-target="#closeTask"
            >
                <i class="glyphicon glyphicon-ok"></i>
                <?=Yii::t('task', 'Close task')?>
            </button>
        <?php } ?>

        <?php if(Yii::$app->user->can(Access::CHANGE_STAGE)) { ?>
            <div class="btn-group">
                <button class="btn btn-app dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                    <span>
                        <i class="glyphicon glyphicon-chevron-right"></i>
                        <?=Yii::t('task', 'Change stage')?>
                    </span>
                    <span class="fa fa-caret-down"></span>
                </button>
                <ul class="dropdown-menu">
                    <?php foreach (ProjectHelper::getStagesList() as $stageId => $stageName) { ?>
                        <li>
                            <a href="<?= ProjectUrl::to([
                                'task/change-stage',
                                'suffix'=>$task->suffix,
                                'index'=>$task->index,
                                'stage'=>$stageId
                            ])?>">
                                <?=Yii::t('task', 'Go to stage') . $stageName ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
    </div>
</div>
<div class="row-fluid task-view">
    <div class="col-md-3 task-dict-block">
        <table class="table table-striped item-val">
            <tbody>
            <tr>
                <td class="item">
                    <?=$task->getAttributeLabel('stage') ?>
                </td>
                <td class="value">
                    <?=$task->stage->name ?>
                </td>
            </tr>
            <tr>
                <td class="item">
                    <?=$task->getAttributeLabel('progress') ?>
                </td>
                <td class="value">
                    <?=HtmlBlock::progressWidget($task->progress)?>
                </td>
            </tr>
        <?php if($task->type) { ?>
            <tr>
                <td class="item">
                    <?=$task->getAttributeLabel('type') ?>
                </td>
                <td class="value">
                    <?=$task->type->name ?>
                </td>
            </tr>
        <?php } ?>
        <?php if($task->category) { ?>
            <tr>
                <td class="item">
                    <?=$task->getAttributeLabel('category') ?>
                </td>
                <td class="value">
                    <?=$task->category->name ?>
                </td>
            </tr>
        <?php } ?>
            <tr>
                <td class="item">
                    <?=$task->getAttributeLabel('assigned') ?>
                </td>
                <td class="value">
                    <?=$task->assigned ? $task->assigned->username : Yii::t('common', 'Not set') ?>
                </td>
            </tr>
            <tr>
                <td class="item">
                    <?=$task->getAttributeLabel('priority') ?>
                </td>
                <td class="value">
                    <?=$task->getPriorityName()?>
                </td>
            </tr>
        <?php if($task->difficulty) { ?>
            <tr>
                <td class="item">
                    <?=$task->getAttributeLabel('difficulty') ?>
                </td>
                <td class="value">
                    <?=$task->difficulty->name ?>
                </td>
            </tr>
        <?php } ?>
            <tr>
                <td class="item">
                    <?=$task->getAttributeLabel('versionOpen') ?>
                </td>
                <td class="value">
                    <?=$task->versionOpen ? $task->versionOpen->name : Yii::t('common', 'Not set')  ?>
                </td>
            </tr>
            <tr>
                <td class="item">
                    <?=$task->getAttributeLabel('created_at') ?>
                </td>
                <td class="value">
                    <?= Yii::$app->formatter->asDatetime($task->created_at) ?>
                </td>
            </tr>
            <tr>
                <td class="item">
                    <?=$task->getAttributeLabel('versionClose') ?>
                </td>
                <td class="value">
                    <?=$task->versionClose ? $task->versionClose->name : Yii::t('common', 'Not set') ?>
                </td>
            </tr>
            <?php if($task->created_at != $task->updated_at) { ?>
                <tr>
                    <td class="item">
                        <?=$task->getAttributeLabel('updated_at') ?>
                    </td>
                    <td class="value">
                        <?= Yii::$app->formatter->asDatetime($task->updated_at) ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-9 task-text-block">
        <h2 class="task-header <?=$task->is_closed?'closed':''?>"><?=$task->getName()?> - <?=$task->caption?></h2>
        <div class="well">
            <?= TextEditorHelper::render($task, 'description'); ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<?= CommentThread::widget([
        'object' => $task,
        'enableNewComment' => !$task->is_closed || $task->project->getConfigItem('enableCommentToClosed')
])?>
<div class="clearfix"></div>
<?php if($canClose) { ?>
    <?= CloseTask::widget(['task' => $task, 'modalId' => 'closeTask'])?>
<?php } ?>
