<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\widgets\UserSelect;
use app\modules\admin\models\Project;

/* @var $this yii\web\View */
/* @var $project app\modules\admin\models\Project */
/* @var $form yii\bootstrap\ActiveForm */

?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php $isEdit = ($project->scenario == Project::SCENARIO_EDIT) ?>

    <?= $form->field($project, 'suffix')
        ->textInput(['maxlength' => true, 'disabled' => $isEdit])
        ->hint($isEdit?false:'Нельзя будет отредактировать');
    ?>

    <?= $form->field($project, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($project, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($project, 'public')->dropDownList($project->getPublicStatusesArray()) ?>

    <?= $form->field($project, 'admin_id')->widget(UserSelect::className(), ['userField' => 'admin']) ?>

    <?= $form->field($project, 'enableCommentProject')->checkbox() ?>
    <?= $form->field($project, 'enableCommentToClosed')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(
                $project->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Update'),
                ['class' => $project->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
