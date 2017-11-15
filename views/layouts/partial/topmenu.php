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
use app\helpers\Access;


if ($projectService->project) {
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
        'brandLabel'            => $brandLabel,
        'brandUrl'              => $brandUrl,
        'options'               => [
            'class' => 'navbar navbar-static-top',
        ],
        'innerContainerOptions' => ['class' => 'container-fluid'],
    ]);

    $items = Yii::$app->projectService->projectMenu;

    $items = array_merge($items, [
        ['label' => Yii::t('common', 'About'), 'url' => ['/main/about']],
    ]);

    if (Yii::$app->user->isGuest) {
        $items = array_merge($items, [
            ['label' => Yii::t('user', 'Login'), 'url' => ['/auth/login']],
            ['label' => Yii::t('user', 'Registration'), 'url' => ['/auth/registration']],
        ]);
    } else {
        $adminItems = [];
        if(Yii::$app->user->can(Access::USER_MANAGEMENT)) {
            $adminItems[] = [
                'label'   => Yii::t('user', 'User Manager'),
                'url'     => ['/admin/user/list'],
            ];
        }
        if(Yii::$app->user->can(Access::ACCESS_MANAGEMENT)) {
            $adminItems[] = [
                'label'   => Yii::t('access', 'Access management'),
                'url'     => ['/admin/access/index'],
            ];
        }
        if(Yii::$app->user->can(Access::PROJECT_MANAGEMENT)) {
            $adminItems[] = [
                'label'   => Yii::t('project', 'Project Manager'),
                'url'     => ['/admin/project/list'],
            ];
        }
        if(count($adminItems)) {
            $items = array_merge($items, [
                [
                    'label' => Yii::t('common', 'Administration'),
                    'items' => $adminItems,
                ],
            ]);
        }

        $items = array_merge($items, [
            [
                'label' => '<i class="fa fa-user"></i>&nbsp;' . Yii::t('user', 'Profile ({user})',
                        ['user' => Yii::$app->user->identity->username]),
                'url'   => ['/user/profile'],
            ],
            [
                'label'       => Yii::t('user', 'Logout'),
                'url'         => ['/auth/logout'],
                'linkOptions' => ['data-method' => 'post'],
            ],
        ]);
    }

    echo Nav::widget([
        'encodeLabels' => false,
        'options'      => ['class' => 'navbar-nav navbar-right'],
        'items'        => $items,
    ]);
    NavBar::end();
    ?>
</header>
