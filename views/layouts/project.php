<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 25.09.17
 * Time: 17:50
 */

use yii\widgets\Breadcrumbs;

use app\helpers\ProjectUrl;
use app\models\entities\Project;

/* @var $this yii\web\View */
/* @var $project Project */
/* @var $content string */

$project = Yii::$app->projectService->project;

$this->title = $project->name;
array_unshift($this->params['breadcrumbs'], $this->title);


?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <section class="content-header">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <div class="row-fluid">
            <?php $active = 'class="active"'; ?>
            <ul class="nav nav-tabs">
                <li <?=$this->context->route == 'project/overview' ? $active : '' ?>>
                    <a href="<?=ProjectUrl::to(['project/overview', 'project' => $project]) ?>">
                        <?=Yii::t('project', 'Overview')?>
                    </a>
                </li>
                <li <?=$this->context->route == 'task/list' ? $active : '' ?>>
                    <a href="<?=ProjectUrl::to(['task/list', 'project' => $project]) ?>">
                        <?=Yii::t('task', 'Tasks')?>
                    </a>
                </li>
                <li <?=$this->context->route == 'task/create' ? $active : '' ?>>
                    <a href="<?=ProjectUrl::to(['task/create', 'project' => $project]) ?>">
                        <?=Yii::t('task', 'Create task')?>
                    </a>
                </li>
                <li <?=strpos($this->context->route, 'project-settings')!==false ? $active : '' ?>>
                    <a href="<?=ProjectUrl::to(['project-settings/main', 'project' => $project]) ?>">
                        <?=Yii::t('project', 'Project settings')?>
                    </a>
                </li>
            </ul>
        </div>
    </section>
    <section class="content">
        <?= $content ?>
    </section>

<?php $this->endContent(); ?>