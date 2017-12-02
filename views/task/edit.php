<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 28.09.17
 * Time: 12:22
 */

use app\models\entities\Project;
use app\helpers\HtmlBlock;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $project Project */
/* @var $task app\models\entities\Task */

$this->title = HtmlBlock::titleString(Yii::t('task', 'Edit task'), $project);
$this->params['breadcrumbs'][] = Yii::t('task', 'Edit task');

?>
<?= $this->render('_form', [
    'task' => $task,
]) ?>
