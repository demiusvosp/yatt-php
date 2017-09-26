<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 27.09.17
 * Time: 0:46
 */
use yii\helpers\Html;

use app\models\entities\Project;
use app\models\entities\Task;

/* @var $this yii\web\View */
/* @var $task Task */

$this->title = Yii::t('task', 'Task: ') . $task->getName();
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    Просмотр задачи <?=$task->getName() ?>
</div>
