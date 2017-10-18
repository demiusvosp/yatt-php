<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 28.09.17
 * Time: 12:22
 */


/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $task app\models\entities\Task */

$this->title = Yii::t('task', 'Create task');
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', [
    'task' => $task,
]) ?>
