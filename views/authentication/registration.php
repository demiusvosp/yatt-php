<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 14.11.16
 * Time: 17:51
 */

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forms\RegistrationForm */

$this->title = Yii::t('user', 'Registration');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('user', 'Fill register fields:') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-registration']); ?>
            <?= $form->field($model, 'username') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'captchaAction' => 'main/captcha',
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ]) ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('user', 'register'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>