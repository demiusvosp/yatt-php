<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\admin\models\Project;

/* @var $this yii\web\View */
/* @var $project Project */

$this->title = $project->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('project', 'Project Manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-solid box-default"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <h1 class="box-title"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="box-body">
        <p>
            <?= Html::a(Yii::t('common', 'Edit'), ['update', 'id' => $project->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('common', 'Delete'), ['delete', 'id' => $project->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('common', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $project,
            'attributes' => [
                'id',
                'suffix',
                'name',
                'description:ntext',
                [
                    'attribute' => 'public',
                    'value' => function($project) {
                        /** @var Project $project */
                        return $project->getPublicStatusName();
                    }
                ],
                'created_at:datetime',
                'updated_at:datetime',
                [
                    'attribute' => 'admin',
                    'label' => Yii::t('project', 'Project admin'),// вобще хорошо бы это доставать из модели, чтобы все названия полей в одном месте
                    'value' => function($project) {
                        /** @var Project $project */
                        return $project->admin ? $project->admin->username : Yii::t('common', 'Not set');
                        //return $user ? $user instanceof User ? $user->username : Yii::t('common', 'Unknow') : Yii::t('common', 'Not set');
                    }
                ],

                'enableCommentProject:boolean',
                'enableCommentToClosed:boolean',

                [
                    'attribute' => 'editorType',
                    'value' => function($project) {
                        /** @var Project $project */
                        //return $project->editorType;
                        return Project::editorTypesLabels()[$project->editorType];
                    }
                ],

            ],
        ]) ?>
    </div>
</div>
