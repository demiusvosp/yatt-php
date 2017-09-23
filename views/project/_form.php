<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\entities\User;

/* @var $this yii\web\View */
/* @var $model app\models\entities\Project */
/* @var $form yii\bootstrap\ActiveForm */

$adminsChoices = [];
/** @var User $user */
foreach (User::getUsersMayProjectList() as $user) {// вобще это не шаблонная логика
    $adminsChoices[$user->id] = $user->username;
}
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'suffix')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'public')->dropDownList($model->getPublicStatusesArray()) ?>

    <?= $form->field($model, 'admin_id')->listBox($adminsChoices) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
