<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 25.09.17
 * Time: 17:50
 */

use yii\widgets\Breadcrumbs;
use app\helpers\Access;
use app\helpers\ProjectUrl;
use app\models\entities\Project;


/* @var $this yii\web\View */
/* @var $project Project */
/* @var $content string */

$project = Yii::$app->projectService->project;

if(!$this->title) {
    $this->title = $project->name;
}
array_unshift($this->params['breadcrumbs'], $project->name);


?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <section class="content-header">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <div class="row-fluid">
            <?php $active = 'class="active"'; ?>
            <ul class="nav nav-tabs">
                <li <?= $this->context->route == 'project/overview' ? $active : '' ?>>
                    <a href="<?= ProjectUrl::to(['project/overview', 'project' => $project]) ?>">
                        <?= Yii::t('project', 'Overview') ?>
                    </a>
                </li>
                <li <?= $this->context->route == 'task/list' ? $active : '' ?>>
                    <a href="<?= ProjectUrl::to(['task/list', 'project' => $project]) ?>">
                        <?= Yii::t('task', 'Tasks') ?>
                    </a>
                </li>
                <?php if (Yii::$app->user->can(Access::OPEN_TASK)) { ?>
                    <li <?=$this->context->route == 'task/open' ? $active : '' ?>>
                        <a href="<?= ProjectUrl::to(['task/open', 'project' => $project]) ?>">
                            <?= Yii::t('task', 'Open task') ?>
                        </a>
                    </li>
                <?php } ?>
                <?php if(Yii::$app->user->can(Access::ADMIN)) { ?>
                    <li <?= strpos($this->context->route, 'project-settings') !== false ? $active : '' ?>>
                        <a href="<?= ProjectUrl::to(['project-settings/main', 'project' => $project]) ?>">
                            <?= Yii::t('project', 'Project settings') ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </section>
    <section class="content">
        <?= $content ?>
    </section>

<?php $this->endContent(); ?>