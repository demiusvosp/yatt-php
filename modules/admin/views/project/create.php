<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\auth\AccessBuilder;
use app\helpers\TextEditorHelper;
use app\widgets\UserSelect;


/* @var $this yii\web\View */
/* @var $project app\models\entities\Project */

$this->title = Yii::$app->name . ' :: ' . Yii::t('project', 'Create Project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('project', 'Project Manager'), 'url' => ['index']];
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
                ->textInput(['maxlength' => true, 'disabled' => true])
                ->hint(Yii::t('project' , 'Cannot be edit'));
            ?>

            <?= $form->field($project, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($project, 'accessTemplate')->dropDownList($accessBuilder->getTemplatesList()); ?>

            <?= $form->field($project, 'admin_id')->widget(UserSelect::className(), ['userField' => 'admin']) ?>

            <?= $form->field($project, 'editorType')->radioList(TextEditorHelper::getTextEditorsList()) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('common', 'Update'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
