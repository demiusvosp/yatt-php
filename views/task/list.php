<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 26.09.17
 * Time: 19:32
 */

use app\helpers\HtmlBlock;
use app\models\entities\Project;
use app\models\forms\TaskListForm;
use app\widgets\TaskListWidget;

/* @var $this yii\web\View */
/* @var $project Project */
/* @var $taskListForm TaskListForm */

$this->title = HtmlBlock::titleString(Yii::t('task', 'Task list'), $project);
$this->params['breadcrumbs'][] = Yii::t('task', 'Task list');

?>

<div class="row-fluid">
    <?= TaskListWidget::widget(['taskListForm' => $taskListForm]); ?>
</div>