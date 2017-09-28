<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;

use app\models\entities\Project;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

const DESCRIPTION_MAX_LEN = 255;

$this->title = Yii::t('project', 'Project Manager');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-solid box-default"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <h1 class="box-title"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="box-body">
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
                [
                    'attribute' => 'description',
                    'value' => function($project) {
                        return StringHelper::truncate($project->description, DESCRIPTION_MAX_LEN);
                    }
                ],
                [
                    'attribute' => 'admin',
                    'label' => Yii::t('project', 'Project admin'),// вобще хорошо бы это доставать из модели, чтобы все названия полей в одном месте
                    'value' => function($project) {
                        /** @var Project $project */
                        return $project->admin ? $project->admin->username : Yii::t('common', 'Not set');
                        //return $user ? $user instanceof User ? $user->username : Yii::t('common', 'Unknow') : Yii::t('common', 'Not set');
                    }
                ],
                [
                    'attribute' => 'public',
                    'value' => function($project) {
                        /** @var Project $project */
                        return $project->getPublicStatusName();
                    }
                ],
                'created_at:datetime',
                'updated_at:datetime',
                // 'admin_id',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
