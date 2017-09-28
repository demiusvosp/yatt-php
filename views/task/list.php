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

use app\models\entities\Project;
use app\models\entities\Task;

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
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'label'  => Yii::t('task', 'ID'),
                'content' => function($task) {
                    return Html::a($task->name, ['task/view', 'suffix' => Yii::$app->projectService->getSuffixUrl(), 'index' => $task->index]);
                },
            ],
            [
                'attribute' => 'caption',
                'label'  => Yii::t('task', 'Caption'),
                'content' => function($task) {
                    return Html::a(StringHelper::truncate($task->caption, COLUMN_MAX_LEN), ['task/view', 'suffix' => Yii::$app->projectService->getSuffixUrl(), 'index' => $task->index]);
                },
            ],
            [
                'attribute' => 'assigned',
                'label' => Yii::t('task', 'Assigned'),
                'content' => function($task) {
                    /** @var Task $task */
                    return $task->assigned ? $task->assigned->username : Yii::t('common', 'Not set');
                    //return $user ? $user instanceof User ? $user->username : Yii::t('common', 'Unknow') : Yii::t('common', 'Not set');
                }
            ],
            'description:ntext',

            'created_at:datetime',
            'updated_at:datetime',
            // 'admin_id',

        ],
    ]); ?>
</div>