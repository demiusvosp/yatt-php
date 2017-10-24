<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\entities\User;

/* @var $this yii\web\View */
/* @var $user app\models\entities\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($user, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($user, 'status')->dropDownList(User::getStatusesArray()) ?>

    <?= $form->field($user, 'password')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($user->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Update'), ['class' => $user->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
