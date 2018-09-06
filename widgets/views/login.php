<?php
/**
 * User: demius
 * Date: 05.09.18
 * Time: 22:53
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $formModel app\models\forms\LoginForm */
/* @var $isModal bool */
?>
<?php if($isModal) { ?>
<div class="modal fade" id="login-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
<?php } ?>
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
                        'action' => Url::to(['/auth/login']),
                        'enableClientValidation' => true,
                        'enableClientScript' => true,
                    ]); ?>

                    <?= $form
                        ->field($formModel, 'username', [
                            'template'=>"{input}<i class=\"glyphicon glyphicon-user form-control-feedback\"></i>\n{hint}\n{error}",
                            'options' => ['class' => "has-feedback"]
                        ])
                        ->textInput(['autofocus' => true, 'placeholder' => true])
                    ?>

                    <?= $form
                        ->field($formModel, 'password', [
                            'template'=>"{input}<i class=\"glyphicon glyphicon-lock form-control-feedback\"></i>\n{hint}\n{error}",
                            'options' => ['class' => "has-feedback"]
                        ])
                        ->passwordInput(['placeholder' => true])
                    ?>

                    <div class="row button-row">
                        <div class="col-xs-8">
                            <?= $form->field($formModel, 'rememberMe')->checkbox() ?>
                        </div>
                        <div class="col-xs-4">
                            <?= Html::submitButton(
                                Yii::t('user', 'Login'),
                                ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']
                            ) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>


                    <div class="login-box-msg">
                        <?= Yii::t('user', 'The installation creates superadmin user <strong>admin</strong> with password <strong>root</strong>.
It is strongly recommended to change it immediately') ?>
                    </div>
                </div>
            </div>
<?php if($isModal) { ?>
        </div>
    </div>
</div>
<?php } ?>