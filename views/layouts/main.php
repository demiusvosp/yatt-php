<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\widgets\Alert;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

use app\models\queries\ProjectQuery;
use app\components\ProjectService;

AppAsset::register($this);
/** @var ProjectService $projectService */
$projectService = Yii::$app->projectService;

$title = Yii::$app->name;
if($projectService->project) {
    $title .= ': ' . $projectService->project->name;
    $brandLabel = $projectService->project->name;
} else {
    $brandLabel = Yii::$app->name;
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
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => $brandLabel,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $items = Yii::$app->projectService->projectMenu;

    $items = array_merge($items, [
        ['label' => Yii::t('common', 'About'), 'url' => ['main/about']],
    ]);

    if(Yii::$app->user->isGuest) {
        $items = array_merge($items, [
            ['label' => Yii::t('user', 'Login'), 'url' => ['auth/login']],
            ['label' => Yii::t('user', 'Registration'), 'url' => ['auth/registration']],
        ]);
    } else {
        // позже здесь будут проверки прав и полномочий
        $items = array_merge($items, [
            ['label' => Yii::t('common', 'Administration'), 'items' => [
                ['label' => Yii::t('user', 'User Manager'), 'items' => []],
                ['label' => Yii::t('project', 'Project Manager'), 'url' => ['pm/list']]
            ]],
        ]);

        $items = array_merge($items, [
            ['label' => Yii::t('user', 'Profile ({user})', ['user' => Yii::$app->user->identity->username]),
                'url' => ['auth/profile']],
            ['label' => Yii::t('user', 'Logout'),
                'url' => ['auth/logout'],
                'linkOptions' => ['data-method' => 'post']],
        ]);
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Alert::widget() ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">Yatt/php <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
