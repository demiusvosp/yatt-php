<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

use dmstr\helpers\AdminLteHelper;
use app\widgets\Alert;

use app\assets\AppAsset;

use app\components\ProjectService;

AppAsset::register($this);
/** @var ProjectService $projectService */
$projectService = Yii::$app->projectService;

$title = Yii::$app->name;
if($projectService->project) {
    $title .= ': ' . $projectService->project->name;
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($title) ?></title>
    <?php $this->head() ?>
</head>
<body class="<?= AdminLteHelper::skinClass() ?> sidebar-mini layout-top-nav" >
<?php $this->beginBody() ?>

<div class="wrapper" style="height: auto; min-height: 100%;">

    <?= $this->render(
        'partial/topmenu.php',
        ['projectService' => $projectService ]
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

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
