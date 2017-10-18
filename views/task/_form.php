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
use app\components\ProjectService;

/** @var ProjectService $projectService */
$projectService = Yii::$app->projectService;

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
            <?= $form->field($model, 'dict_stage_id')->dropDownList($projectService->getStagesList()) ?>
        </p>
        <p>
            Прогресс
        </p>
        <p>
            <?= $form->field($model, 'dict_type_id')->dropDownList($projectService->getTypesList()) ?>
        </p>
        <p>
            <?= $form->field($model, 'dict_category_id')->dropDownList($projectService->getCategoryList()) ?>
        </p>
        <p>
            <?= $form->field($model, 'assigned_id')->listBox($adminsChoices) ?>
        </p>
        <p>
            <?= $form->field($model, 'priority')->dropDownList(Task::priorityLabels()) ?>
        </p>
        <p>
            <?= $form->field($model, 'dict_difficulty_id')->dropDownList($projectService->getDifficultyList()) ?>
        </p>
        <p>
            <?= $form->field($model, 'dict_version_open_id')->dropDownList($projectService->getVersionList(true)) ?>
        </p>
        <p>
            <?= $form->field($model, 'dict_version_close_id')->dropDownList($projectService->getVersionList(false)) ?>
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
