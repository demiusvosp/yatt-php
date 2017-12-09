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
<div class="row"> <!-- Это не в этом блоке, и требует переверстки лейаутов -->
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="box box-bordered box-success">
            <div class="box-header">
                <h3 class="box-title"><?= Yii::t('user', 'Change password:') ?></h3>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'form-changePassword']); ?>
            <div class="box-body">
                <div class="form-group">
                    <?= $form->field($changePasswordForm, 'old_password')->passwordInput() ?>
                </div>
                <div class="form-group">
                    <?= $form->field($changePasswordForm, 'new_password')->passwordInput() ?>
                    <?= $form->field($changePasswordForm, 'new_password_repeat')->passwordInput() ?>
                </div>
            </div>
            <div class="box-footer">
                <?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-bordered box-success">
            <div class="box-header">
                <h3 class="box-title"><?= Yii::t('user', 'Change username or email:') ?></h3>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'form-changeMainFields']); ?>
            <div class="box-body">
                <div class="form-group">
                    <?= $form->field($changeMainFieldsForm, 'username') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($changeMainFieldsForm, 'email') ?>
                    <span class="label label-warning"><?= Yii::t('user', 'You will need to confirm over email') ?></span>
                </div>
            </div>
            <div class="box-footer">
                <?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>