<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\entities\User;
use app\models\queries\UserQuery;


/* @var $this yii\web\View */
/* @var $project app\models\entities\Project */
/* @var $form yii\bootstrap\ActiveForm */

$adminsChoices = [];
/** @var User $user */
foreach (UserQuery::getUsersMayProjectList() as $user) {// вобще это не шаблонная логика
    $adminsChoices[$user->id] = $user->username;
}
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($project, 'suffix')->textInput(['maxlength' => true]) ?>

    <?= $form->field($project, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($project, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($project, 'public')->dropDownList($project->getPublicStatusesArray()) ?>

    <?= $form->field($project, 'admin_id')->listBox($adminsChoices) ?>

    <div class="form-group">
        <?= Html::submitButton($project->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Update'), ['class' => $project->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
