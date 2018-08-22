<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forms\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('user', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-box">
    <div class="login-logo">
        <a href="<?= Yii::$app->homeUrl?>">
            <h1><?= Html::encode(Yii::$app->name) ?></h1>
        </a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg"><?= Yii::t('user' , 'Sign in to start your session')?></p>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
        ]); ?>

        <?= $form
            ->field($model, 'username', [
                'template'=>"{input}<i class=\"glyphicon glyphicon-user form-control-feedback\"></i>\n{hint}\n{error}",
                'options' => ['class' => "has-feedback"]
            ])
            ->textInput(['autofocus' => true, 'placeholder' => true])
        ?>

        <?= $form
            ->field($model, 'password', [
                'template'=>"{input}<i class=\"glyphicon glyphicon-lock form-control-feedback\"></i>\n{hint}\n{error}",
                'options' => ['class' => "has-feedback"]
            ])
            ->passwordInput(['placeholder' => true])
        ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <div class="col-xs-4">
                <?= Html::submitButton(Yii::t('user', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="login-box-msg">
            <?= Yii::t('user', 'The installation creates superadmin user <strong>admin</strong> with password <strong>root</strong>.
It is strongly recommended to change it immediately') ?>
        </div>
    </div>
</div>
