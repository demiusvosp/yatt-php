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
?>
<div class="row">
    здесь будут фильтры
</div>
<div class="row">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'caption',
            [
                'attribute' => 'assigned',
                'label' => Yii::t('task', 'Assigned'),
                'value' => function($task) {
                    /** @var Task $task */
                    return $task->assigned ? $task->assigned->username : Yii::t('common', 'Not set');
                    //return $user ? $user instanceof User ? $user->username : Yii::t('common', 'Unknow') : Yii::t('common', 'Not set');
                }
            ],
            'description:ntext',

            'created_at:datetime',
            'updated_at:datetime',
            // 'admin_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>