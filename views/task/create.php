<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 26.09.17
 * Time: 14:48
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use app\models\entities\User;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\entities\Task */

$this->title = Yii::t('task', 'Create task');
$this->params['breadcrumbs'][] = $this->title;

// врменное решение
$adminsChoices = [];
/** @var User $user */
foreach (User::getUsersMayProjectList() as $user) {// вобще это не шаблонная логика
    $adminsChoices[$user->id] = $user->username;
}

?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4 task-dict-block">
        здесь справочники

        <?= $form->field($model, 'assigned')->listBox($adminsChoices) ?>

    </div>

    <div class="col-md-8">
        <?= $form->field($model, 'caption')->textInput() ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 10]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
