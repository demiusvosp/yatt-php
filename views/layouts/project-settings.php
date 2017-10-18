<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 25.09.17
 * Time: 17:50
 */


use app\helpers\ProjectUrl;
use app\models\entities\Project;

/* @var $this yii\web\View */
/* @var $project Project */
/* @var $content string */

$project = Yii::$app->projectService->project;

$this->title = Yii::t('project', 'Settings');
array_unshift($this->params['breadcrumbs'], $this->title);

?>
<?php $this->beginContent('@app/views/layouts/project.php'); ?>
    <section class="content-submenu">
        <div class="col-md-2 col-sm-2">
            <?php $active = 'class="active"'; ?>
            <ul class="nav nav-tabs tabs-left">
                <li <?=$this->context->route == 'project-settings/main' ? $active : '' ?>>
                    <a href="<?=ProjectUrl::to(['project-settings/main', 'project' => $project]) ?>">
                        <?=Yii::t('project/settings', 'Main')?>
                    </a>
                </li>
                <li <?=$this->context->route == 'project-settings/stages' ? $active : '' ?>>
                    <a href="<?=ProjectUrl::to(['project-settings/stages', 'project' => $project]) ?>">
                        <?=Yii::t('project/settings', 'Stages')?>
                    </a>
                </li>
                <li <?=$this->context->route == 'project-settings/types' ? $active : '' ?>>
                    <a href="<?=ProjectUrl::to(['project-settings/types', 'project' => $project]) ?>">
                        <?=Yii::t('project/settings', 'Types')?>
                    </a>
                </li>
                <li <?=$this->context->route == 'project-settings/categories' ? $active : '' ?>>
                    <a href="<?=ProjectUrl::to(['project-settings/categories', 'project' => $project]) ?>">
                        <?=Yii::t('project/settings', 'Categories')?>
                    </a>
                </li>
                <li <?=$this->context->route == 'project-settings/versions' ? $active : '' ?>>
                    <a href="<?=ProjectUrl::to(['project-settings/versions', 'project' => $project]) ?>">
                        <?=Yii::t('project/settings', 'Versions')?>
                    </a>
                </li>
                <li <?=$this->context->route == 'project-settings/difficulties' ? $active : '' ?>>
                    <a href="<?=ProjectUrl::to(['project-settings/difficulties', 'project' => $project]) ?>">
                        <?=Yii::t('project/settings', 'Difficulty levels')?>
                    </a>
                </li>
            </ul>
        </div>
    </section>
    <section class="content">
        <div class="col-md-10 col-sm-10">
            <?= $content ?>
        </div>
    </section>

<?php $this->endContent(); ?>