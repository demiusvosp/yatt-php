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
/* @var $task Task */

// врменное решение
$adminsChoices = [];
/** @var User $user */
foreach (User::getUsersMayProjectList() as $user) {// вобще это не шаблонная логика
    $adminsChoices[$user->id] = $user->username;
}
// простое решение выбора прогресса (как в flyspray)
$progressList = [];
for($i = 0; $i <= 100; $i += 10) {
    $progressList[$i] = $i . '%';
}
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4 task-dict-block">
        <div class="row-fluid">
            <?= $form->field($task, 'dict_stage_id')->dropDownList($projectService->getStagesList()) ?>
        </div>
        <div class="row-fluid">
            <?= $form->field($task, 'progress')->dropDownList($progressList) ?>
        </div>
        <div class="row-fluid">
            <?= $form->field($task, 'dict_type_id')->dropDownList($projectService->getTypesList()) ?>
        </div>
        <div class="row-fluid">
            <?= $form->field($task, 'dict_category_id')->dropDownList($projectService->getCategoryList()) ?>
        </div>
        <div class="row-fluid">
            <?= $form->field($task, 'assigned_id')->listBox($adminsChoices) ?>
        </div>
        <div class="row-fluid">
            <?= $form->field($task, 'priority')->dropDownList(Task::priorityLabels()) ?>
        </div>
        <div class="row-fluid">
            <?= $form->field($task, 'dict_difficulty_id')->dropDownList($projectService->getDifficultyList()) ?>
        </div>
        <div class="row-fluid">
            <?= $form->field($task, 'dict_version_open_id')->dropDownList($projectService->getVersionList(true)) ?>
        </div>
        <div class="row-fluid">
            <?= $form->field($task, 'dict_version_close_id')->dropDownList($projectService->getVersionList(false)) ?>
        </div>
    </div>

    <div class="col-md-8 task-text-block">
        <?php if(!$task->isNewRecord) { ?>
            <h2><?=$task->getName()?></h2>
        <?php } ?>
        <?= $form->field($task, 'caption')->textInput() ?>
        <?= $form->field($task, 'description')->textarea(['rows' => 10]) ?>

        <div class="form-group">
            <?= Html::submitButton($task->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Update'), ['class' => $task->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
