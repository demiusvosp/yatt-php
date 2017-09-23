<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 14.11.16
 * Time: 20:58
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $changePasswordForm app\models\forms\ChangePasswordForm */
/* @var $changeMainFieldsForm app\models\forms\ChangeMainFieldsForm */

$username = Yii::$app->user->identity ? Yii::$app->user->identity->username : '(anonymous)';

$this->title = Yii::t('user', '{user} profile', ['user' => $username]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('user', 'User profile') ?></p>

    <div class="row">
        <div class="col-lg-5 form-border">
            <p><?= Yii::t('user', 'Change password:') ?></p>
            <?php $form = ActiveForm::begin(['id' => 'form-changePassword']); ?>
            <div class="form-group">
                <?= $form->field($changePasswordForm, 'old_password')->passwordInput() ?>
            </div>
            <div class="form-group">
                <?= $form->field($changePasswordForm, 'new_password')->passwordInput() ?>
                <?= $form->field($changePasswordForm, 'new_password_repeat')->passwordInput() ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-5 form-border">
            <p><?= Yii::t('user', 'Change username or email:') ?></p>
            <?php $form = ActiveForm::begin(['id' => 'form-changeMainFields']); ?>
                <div class="form-group">
                    <?= $form->field($changeMainFieldsForm, 'username') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($changeMainFieldsForm, 'email') ?>
                    <span class="label label-warning"><?= Yii::t('user', 'You will need to confirm over email') ?></span>
                </div>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>