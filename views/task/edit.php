<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 28.09.17
 * Time: 12:22
 */

use yii\helpers\Html;

use app\models\entities\User;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\entities\Task */

$this->title = Yii::t('task', 'Create task');
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
