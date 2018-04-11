<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\entities\User */

$this->title = Yii::$app->name . ' :: ' . Yii::t('admin/user', 'Update User') . ': ' . $user->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin/user', 'User Manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = Yii::t('common', 'Update');
?>
<div class="box box-solid box-default"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <h1 class="box-title"><?= Html::encode(Yii::t('admin/user', 'Update User') . ': ' . $user->username) ?></h1>
    </div>

    <div class="box-body">
        <?= $this->render('_form', [
            'user' => $user,
        ]) ?>
    </div>
</div>
