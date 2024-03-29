<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\helpers\TextEditorHelper;


/* @var $this yii\web\View */
/* @var $project app\models\entities\Project */

$this->title = Yii::$app->name . ' :: ' . Yii::t('admin/project', 'Administrate') . ': ' . $project->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin/project', 'Project Manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $project->name, 'url' => ['view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = Yii::t('admin/project', 'Administrate');
?>
<div class="box box-solid box-default"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <h1 class="box-title"><?= Html::encode(Yii::t('project', 'Update Project')) ?></h1>
    </div>

    <div class="box-body">
        <div class="project-form">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($project, 'suffix')
                ->textInput(['maxlength' => true, 'disabled' => true]);
            ?>

            <?= $form->field($project, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($project, 'description')->editor(['rows' => 6]) ?>

            <?= $form->field($project, 'enableCommentProject')->checkbox() ?>
            <?= $form->field($project, 'enableCommentToClosed')->checkbox() ?>

            <?= $form->field($project, 'editorType')->radioList(TextEditorHelper::getTextEditorsList()) ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('common', 'Create'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
