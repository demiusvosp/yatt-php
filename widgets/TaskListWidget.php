<?php
/**
 * User: demius
 * Date: 01.10.18
 * Time: 23:55
 */

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\grid\GridView;
use app\helpers\HtmlBlock;
use app\helpers\ProjectUrl;
use app\models\entities\Task;
use app\models\forms\TaskListForm;

class TaskListWidget extends Widget
{
    const COLUMN_MAX_LEN = 255;
    const TOOLTIP_LENGTH = 255;

    /** @var TaskListForm */
    public $taskListForm = null;

    public function init()
    {

        parent::init();
    }

    public function run()
    {
        echo GridView::widget([
            'dataProvider'   => $this->taskListForm->getDataProvider(),
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
                    'attribute' => 'id',
                    'contentOptions'   => ['class' => 'item-name'],
                    'label'     => Yii::t('task', 'ID'),
                    'content'   => function ($task) {
                        return Html::a(
                            $task->name,
                            ProjectUrl::toWithCurrent(['task/view', 'index' => $task->index])
                        );
                    },
                ],
                [
                    'attribute' => 'category',
                    'label'     => Yii::t('dicts', 'Category'),
                ],
                [
                    'attribute' => 'type',
                    'label'     => Yii::t('dicts', 'Type'),
                ],
                [
                    'attribute' => 'caption',
                    'contentOptions'   => ['class' => 'item-caption'],
                    'label'     => Yii::t('task', 'Caption'),
                    'content'   => function ($task) {
                        /** @var Task $task */
                        return Html::a(
                            StringHelper::truncate($task->caption, static::COLUMN_MAX_LEN),
                            ProjectUrl::toWithCurrent(['task/view', 'index' => $task->index]),
                            ['tooltip' => StringHelper::truncate($task->description, static::TOOLTIP_LENGTH)]
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
                    'attribute' => 'assigned',
                    'content'        => function ($task) {
                        /** @var Task $task */
                        return HtmlBlock::userItem($task->assigned);
                    },
                ],

                [
                    'attribute' => 'stage',
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
                    'attribute' => 'versionOpen',
                    'label'     => Yii::t('dicts', 'Open in version'),
                ],
                'updated_at:datetime',
                [
                    'attribute' => 'versionClose',
                    'label'     => Yii::t('dicts', 'Сoming in version'),
                ],
            ],
        ]);
    }
}