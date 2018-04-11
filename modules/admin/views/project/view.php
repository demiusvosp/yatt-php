<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\auth\Permission;
use app\helpers\HtmlBlock;
use app\helpers\ProjectUrl;
use app\helpers\TextEditorHelper;
use app\modules\admin\models\Project;


/* @var $this yii\web\View */
/* @var $project Project */

$this->title                   = Yii::$app->name . ' :: ' . Yii::t(
        'admin/project',
        'Project Manager'
    ) . ': ' . $project->name;
$this->params['breadcrumbs'][] = [
        'label' => Yii::t('admin/project', 'Project Manager'),
        'url' => ['index']
];
$this->params['breadcrumbs'][] = $project->name;
?>
<div class="box box-solid box-default"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <h1 class="box-title"><?= Html::encode(Yii::t('admin/project', 'Project Manager') . ': ' . $project->name) ?></h1>
    </div>

    <div class="box-body">
        <p>
            <?= Html::a(
                Yii::t('admin/project', 'Administrate'),
                ['update', 'id' => $project->id],
                ['class' => 'btn btn-primary']
            ) ?>
            <?= Html::a(
                Yii::t('project/settings', 'Main settings'),
                ProjectUrl::to(['/project-settings/main', 'project' => $project]),
                ['class' => 'btn btn-primary']
            ) ?>
            <?= Html::a(
                Yii::t('common', 'Delete'),
                ['delete', 'id' => $project->id],
                [
                    'class' => 'btn btn-danger ' . ($project->canDelete() ? '' : 'disabled'),
                    'data'  => [
                        'confirm' => Yii::t('common', 'Are you sure you want to delete this item?'),
                        'method'  => 'post',
                        'toggle'  => 'tooltip',
                    ],
                    'title' => ($project->canDelete() ? Yii::t('admin/project', 'Delete project') : Yii::t('admin/project',
                        'Cannot delete project with tasks')),
                ]
            ) ?>
            <?= Html::a(
                ($project->archived) ? Yii::t('admin/project', 'From archive') : Yii::t('admin/project', 'To archive'),
                ['archive', 'id' => $project->id],
                [
                    'class' => 'btn btn-default',
                    'data'  => [
                        'confirm' => ($project->archived) ? Yii::t('admin/project',
                            'Are you sure you want to active this project?') : Yii::t('admin/project',
                            'Are you sure you want to archive this project?'),
                        'method'  => 'post',
                    ],
                ]
            ) ?>
        </p>

        <?= DetailView::widget([
            'model'      => $project,
            'attributes' => [
                'id',
                'suffix',
                'name',
                [
                    'attribute' => 'description',
                    'format'    => 'html',
                    'value'     => function ($project) {
                        /** @var Project $project */
                        return TextEditorHelper::render($project, 'description');
                    },
                ],
                'created_at:datetime',
                'updated_at:datetime',
                [
                    'attribute' => 'admin',
                    'format' => 'raw',
                    'value' => function($project) {
                        $value = [];
                        $admins = Yii::$app->get('authManager')
                            ->getUsersByRole(Permission::getFullName(Permission::PROJECT_SETTINGS, $project));
                        foreach ($admins as $admin) {
                            $value[] = HtmlBlock::userItem($admin);
                        }
                        return implode(', ', $value);
                    }
                ],

                'enableCommentProject:boolean',
                'enableCommentToClosed:boolean',

                [
                    'attribute' => 'editorType',
                    'value'     => function ($project) {
                        /** @var Project $project */
                        return TextEditorHelper::getTextEditorsList()[$project->editorType];
                    },
                ],

            ],
        ]) ?>
    </div>
</div>
