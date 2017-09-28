<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 28.09.17
 * Time: 12:22
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use app\models\entities\Task;
use app\models\entities\User;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model Task */

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
        <p>
            Состояние
        </p>
        <p>
            Прогресс
        </p>
        <p>
            Тип задачи
        </p>
        <p>
            Категория/подсистема
        </p>
        <p>
            <?= $form->field($model, 'assigned_id')->listBox($adminsChoices) ?><br>
        </p>
        <p>
            Критичность
        </p>
        <p>
            Приоритет
        </p>
        <p>
            Обнаруженна в версии
        </p>
        <p>
            Ожидается в версии
        </p>
    </div>

    <div class="col-md-8 task-text-block">
        <?php if(!$model->isNewRecord) { ?>
            <h2><?=$model->getName()?></h2>
        <?php } ?>
        <?= $form->field($model, 'caption')->textInput() ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 10]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
