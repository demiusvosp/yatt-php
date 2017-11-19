<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 26.09.17
 * Time: 19:32
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;
use app\models\entities\Task;
use app\helpers\HtmlBlock;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('task', 'Task list');
$this->params['breadcrumbs'][] = $this->title;

const COLUMN_MAX_LEN = 255;
?>
<div class="row-fluid">
    здесь будут фильтры
</div>
<div class="row-fluid">
    <?= GridView::widget([
        'dataProvider'   => $dataProvider,
        'options'        => ['class' => 'task_list'],
        'captionOptions' => ['class' => 'task_list_caption'],
        'rowOptions'     => function ($model, $key, $index, $grid) {
            /** @var Task $model */
            if ($model->is_closed) {
                return ['class' => 'closed'];
            }

            return '';
        },
        'columns'        => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'contentOptions'   => ['class' => 'item-name'],
                'label'     => Yii::t('task', 'ID'),
                'content'   => function ($task) {
                    return Html::a(
                        $task->name,
                        ['task/view', 'suffix' => Yii::$app->projectService->getSuffixUrl(), 'index' => $task->index]
                    );
                },
            ],
            [
                'attribute' => 'category.name',
                'label'     => Yii::t('dicts', 'Category'),
            ],
            [
                'attribute' => 'type.name',
                'label'     => Yii::t('dicts', 'Type'),
            ],
            [
                'attribute' => 'caption',
                'contentOptions'   => ['class' => 'item-caption'],
                'label'     => Yii::t('task', 'Caption'),
                'content'   => function ($task) {
                    //@TODO при наведении показывать тултип с описанием (или нет)
                    /** @var Task $task */
                    return Html::a(
                        StringHelper::truncate($task->caption, COLUMN_MAX_LEN),
                        ['task/view', 'suffix' => Yii::$app->projectService->getSuffixUrl(), 'index' => $task->index]
                    );
                },
            ],
            [
                'attribute'      => 'priority',
                'content'        => function ($task) {
                    /** @var Task $task */
                    return $task->getPriorityName();
                },
                'contentOptions' => function ($task, $key, $index, $column) {
                    return ['class' => 'priority ' . Task::priorityStyles()[$task->priority]];
                },
            ],
            [
                'attribute' => 'assigned.username',
                'label'     => Yii::t('task', 'Assigned'),
            ],

            [
                'attribute' => 'stage.name',
                'label'     => Yii::t('dicts', 'Stage'),
            ],
            [
                'attribute' => 'progress',
                'content'   => function ($task) {
                    /** @var Task $task */
                    return HtmlBlock::progressWidget($task->progress);
                },
            ],

            'created_at:datetime',
            [
                'attribute' => 'versionOpen.name',
                'label'     => Yii::t('dicts', 'Open in version'),
            ],
            'updated_at:datetime',
            [
                'attribute' => 'versionClose.name',
                'label'     => Yii::t('dicts', 'Сoming in version'),
            ],
            // 'admin_id',

        ],
    ]); ?>
</div>