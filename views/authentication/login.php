<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forms\LoginForm */

use app\widgets\LoginWidget;

$this->title = Yii::t('user', 'Login');
$this->params['breadcrumbs'][] = $this->title;

echo LoginWidget::widget(['isModal' => false]);

?>