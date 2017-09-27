<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 26.09.17
 * Time: 19:32
 */
use yii\helpers\Html;
use yii\grid\GridView;

use app\models\entities\Project;
use app\models\entities\Task;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('task', 'Task list');
$this->params['breadcrumbs'][] = $this->title;

const COLUMN_MAX_LEN = 255;
?>
<div class="row">
    здесь будут фильтры
</div>
<div class="row">
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
                    // вот эту еболу, как и создание ссылок надо завернуть в хелперы
                    if(strlen($task->caption) > COLUMN_MAX_LEN) {
                        $caption = substr($task->caption, 0, COLUMN_MAX_LEN - 3) . '...';
                    } else {
                        $caption = $task->caption;
                    }
                    return Html::a($caption, ['task/view', 'suffix' => Yii::$app->projectService->getSuffixUrl(), 'index' => $task->index]);
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