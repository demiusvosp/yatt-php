<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

use dmstr\helpers\AdminLteHelper;
use dmstr\widgets\Alert;
use app\assets\AppAsset;


AppAsset::register($this);


if(!$this->title) {
    $this->title = Yii::$app->name . ' :: ' . Yii::t('common', 'Login page');
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="<?= AdminLteHelper::skinClass() ?> hold-transition login-page" >
<?php $this->beginBody() ?>

    <?= Alert::widget() ?>
    <?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
