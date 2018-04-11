<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;
use app\components\auth\Permission;
use app\helpers\HtmlBlock;
use app\models\entities\Project;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

const DESCRIPTION_MAX_LEN = 255;

$this->title = Yii::$app->name . ' :: ' . Yii::t('admin/project', 'Project Manager');
$this->params['breadcrumbs'][] = Yii::t('admin/project', 'Project Manager');
?>
<div class="box box-solid box-default"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <h1 class="box-title"><?= Html::encode(Yii::t('admin/project', 'Project Manager')) ?></h1>
    </div>

    <div class="box-body">
        <p>
            <?= Html::a(Yii::t('project', 'Create Project'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions'     => function ($model) {
                /** @var Project $model */
                if ($model->archived) {
                    return ['class' => 'archived'];
                }

                return '';
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'suffix',
                'name',
                [
                    'attribute' => 'name',
                    'content' => function($project) {
                        return Html::a(
                            $project->name,
                            ['view', 'id' => $project->id]
                        );
                    },
                ],
                [
                    'attribute' => 'description',
                    'contentOptions' => ['class' => 'description-in-list'],
                    'value' => function($project) {
                        return StringHelper::truncate($project->description, DESCRIPTION_MAX_LEN);
                    }
                ],
                [
                    'attribute' => 'admin',
                    'content' => function($project) {
                        $value = [];
                        $admins = Yii::$app->get('authManager')
                            ->getUsersByPermission(Permission::getFullName(Permission::PROJECT_SETTINGS, $project));
                        foreach ($admins as $admin) {
                            $value[] = HtmlBlock::userItem($admin);
                        }
                        return implode(', ', $value);
                    }
                ],
                'created_at:datetime',
                'updated_at:datetime',


                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update}',
                ],
            ],
        ]); ?>
    </div>
</div>
