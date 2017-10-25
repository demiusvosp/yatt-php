<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 27.09.17
 * Time: 16:20
 */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\helpers\ProjectUrl;


if($projectService->project) {
    $brandLabel = $projectService->project->name;
    $brandUrl = ProjectUrl::to(['project/overview', 'project' => $projectService->project]);
} else {
    $brandLabel = Yii::$app->name;
    $brandUrl = Yii::$app->homeUrl;
}
?>
<header class="main-header">

    <?php
    NavBar::begin([
        'brandLabel' => $brandLabel,
        'brandUrl' => $brandUrl,
        'options' => [
            'class' => 'navbar navbar-static-top',
        ],
        'innerContainerOptions' => [ 'class' => 'container-fluid' ],
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
        if(Yii::$app->user->can('root')) {
            $items = array_merge($items, [
                [
                    'label' => Yii::t('common', 'Administration'),
                    'items' => [
                        ['label' => Yii::t('user', 'User Manager'), 'url' => ['/admin/user/list']],
                        ['label' => Yii::t('project', 'Project Manager'), 'url' => ['/admin/project/list']]
                    ]
                ],
            ]);
        }

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
</header>
