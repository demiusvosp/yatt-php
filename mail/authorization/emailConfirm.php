<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 14.11.16
 * Time: 17:21
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/email-confirm', 'token' => $user->user_token]);
?>

Здравствуйте, <?= Html::encode($user->username) ?>!

Для подтверждения адреса пройдите по ссылке:

<?= Html::a(Html::encode($confirmLink), $confirmLink) ?>

Если Вы не регистрировались на сайте <?= Yii::$app->name ?>, то просто удалите это письмо.