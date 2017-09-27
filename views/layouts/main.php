<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;


use yii\widgets\Breadcrumbs;
use dmstr\helpers\AdminLteHelper;
use app\widgets\Alert;

use app\assets\AppAsset;

use app\models\queries\ProjectQuery;
use app\components\ProjectService;

dmstr\web\AdminLteAsset::register($this);
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
        'topmenu.php',
        ['projectService' => $projectService ]
    ) ?>

    <?php /*= $this->render(
        'leftmenu.php',
        ['directoryAsset' => $directoryAsset, 'projectService' => $projectService]
    ) нам несколько избыточно левое меню. Потом может быть добавим*/
    ?>

    <div class="content-wrapper">
        <section class="content-header">
            <?= Alert::widget() ?>
            <?= ''; /* пока не ясно как его адекватно выводить. см #156 Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ])*/ ?>

        </section>
        <section class="content">
            <?= $content ?>
        </section>
    </div>

    <?= $this->render(
        'footer.php',
        []
    ) ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
