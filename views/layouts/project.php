<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 25.09.17
 * Time: 17:50
 */

use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

use app\models\entities\Project;
use app\components\ProjectService;

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
                    <a href="<?=Url::to(['project/overview', 'suffix' => $project->suffix]) ?>"><?=Yii::t('project', 'Overview')?></a>
                </li>
                <li <?=$this->context->route == 'task/list' ? $active : '' ?>>
                    <a href="<?=Url::to(['task/list', 'suffix' => $project->suffix]) ?>"><?=Yii::t('task', 'Tasks')?></a>
                </li>
                <li <?=$this->context->route == 'task/create' ? $active : '' ?>>
                    <a href="<?=Url::to(['task/create', 'suffix' => $project->suffix]) ?>"><?=Yii::t('task', 'Create task')?></a>
                </li>
            </ul>
        </div>
    </section>
    <section class="content">
        <?= $content ?>
    </section>

<?php $this->endContent(); ?>