<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

use dmstr\helpers\AdminLteHelper;
use dmstr\widgets\Alert;
use app\assets\AppAsset;
use app\helpers\ProjectHelper;
use app\widgets\LoginWidget;


AppAsset::register($this);


if(!$this->title) {
    $this->title = Yii::$app->name;
    $project = ProjectHelper::currentProject();
    if ($project) {
        $this->title .= ' :: ' . $project->name;
    }
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
<body class="<?= AdminLteHelper::skinClass() ?> sidebar-mini layout-top-nav" >
<?php $this->beginBody() ?>

<div class="wrapper" style="height: auto; min-height: 100%;">

    <?= $this->render(
        'partial/topmenu.php',
        []
    ) ?>

    <?php /*= $this->render(
        'partial/leftmenu.php',
        []
    ) нам несколько избыточно левое меню. Потом может быть добавим*/
    ?>

    <div class="content-wrapper">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>

    <?= $this->render(
        'partial/footer.php',
        []
    ) ?>
</div>

<?= (Yii::$app->user->isGuest) ? LoginWidget::widget([]) : '' ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
