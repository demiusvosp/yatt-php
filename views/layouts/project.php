<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 25.09.17
 * Time: 17:50
 */

use yii\helpers\Url;
use app\models\entities\Project;

/* @var $this yii\web\View */
/* @var $project Project */
/* @var $content string */
$project = $this->params['project'];

$this->title = $project->name;
$this->params['breadcrumbs'][] = $this->title;


?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <h1><?=$project->name ?></h1>

    <ul class="nav nav-tabs">
        <li class="active">
            <a href="<?=Url::to(['project/overview', 'suffix' => $project->suffix]) ?>"><?=Yii::t('project', 'Overview')?></a>
        </li>
        <li>
            <a href="<?=Url::to(['task/list', 'suffix' => $project->suffix]) ?>"><?=Yii::t('task', 'Tasks')?></a>
        </li>
        <li>
            <a href="<?=Url::to(['task/create', 'suffix' => $project->suffix]) ?>"><?=Yii::t('task', 'Create task')?></a>
        </li>
    </ul>

    <?= $content ?>

<?php $this->endContent(); ?>