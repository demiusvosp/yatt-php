<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 26.09.17
 * Time: 14:48
 */


/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $task app\models\entities\Task */

$this->title = Yii::t('task', 'Open task');
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', [
    'task' => $task,
]) ?>

