<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\entities\Project;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('project', 'Project Manager');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('project', 'Create Project'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'suffix',
            'name',
            'description:ntext',
            [
                'attribute' => 'admin',
                'label' => 'Администратор',
                'value' => function($project) {
                    /** @var Project $project */
                    return $project->admin ? $project->admin->username : Yii::t('common', 'Not set');
                    //return $user ? $user instanceof User ? $user->username : Yii::t('common', 'Unknow') : Yii::t('common', 'Not set');
                }
            ],
            [
                'attribute' => 'public',
                'label' => 'Виден',
                'value' => function($project) {
                    /** @var Project $project */
                    return $project->getPublicStatusName();
                }
            ],
            // 'config:ntext',
            // 'admin_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>