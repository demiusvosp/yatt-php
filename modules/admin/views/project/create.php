<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\auth\AccessBuilder;
use app\helpers\TextEditorHelper;


/* @var $this yii\web\View */
/* @var $project app\models\entities\Project */

$this->title = Yii::$app->name . ' :: ' . Yii::t('project', 'Create Project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin/project', 'Project Manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('project', 'Create Project');

/** @var AccessBuilder $accessBuilder */
$accessBuilder = Yii::$app->get('accessBuilder');
?>
<div class="box box-solid box-default"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <h1 class="box-title"><?= Html::encode(Yii::t('project', 'Create Project')) ?></h1>
    </div>

    <div class="box-body">
        <div class="project-form">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($project, 'suffix')
                ->textInput(['maxlength' => true])
                ->hint(Yii::t('project' , 'Cannot be edit'));
            ?>

            <?= $form->field($project, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($project, 'accessTemplate')->dropDownList($accessBuilder->getTemplatesList()); ?>

            <?= $form->field($project, 'editorType')->radioList(TextEditorHelper::getTextEditorsList()) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('common', 'Create'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
