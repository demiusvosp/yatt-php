<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 26.09.17
 * Time: 14:48
 */

use app\helpers\HtmlBlock;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $project */
/* @var $task app\models\entities\Task */

$this->title = HtmlBlock::titleString(Yii::t('task', 'Open task'), $project);
$this->params['breadcrumbs'][] = Yii::t('task', 'Open task');

?>
<?= $this->render('_form', [
    'task' => $task,
]) ?>

