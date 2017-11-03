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
use app\widgets\UserSelect;
use app\components\ProjectService;

/** @var ProjectService $projectService */
$projectService = Yii::$app->projectService;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $task Task */

// простое решение выбора прогресса (как в flyspray)
$progressList = [];
for($i = 0; $i <= 100; $i += 10) {
    $progressList[$i] = $i . '%';
}
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4 task-dict-block">
        <?php /* $this->renderFile('partial/dictSelect.twig', ['form'=>$form, 'task'=>$task, 'choices'=>$projectService->getStagesList()])
    чтобы подключить twig partial, необходимо и этот шаблон и create/update сделать twig'ом. Сейчас не до того. */ ?>
        <?php if(count($projectService->getStagesList()) > 1) { ?>
            <div class="row-fluid">
                <?= $form->field($task, 'dict_stage_id')
                    ->dropDownList($projectService->getStagesList())
                ?>
            </div>
        <?php } ?>
        <div class="row-fluid">
            <?= $form->field($task, 'progress')
                ->dropDownList($progressList)
            ?>
        </div>
        <?php if(count($projectService->getTypesList()) > 1) { ?>
            <div class="row-fluid">
                <?= $form->field($task, 'dict_type_id')
                    ->dropDownList($projectService->getTypesList())
                ?>
            </div>
        <?php } ?>
        <?php if(count($projectService->getCategoryList()) > 1) { ?>
            <div class="row-fluid">
                <?= $form->field($task, 'dict_category_id')
                    ->dropDownList($projectService->getCategoryList())
                ?>
            </div>
        <?php } ?>
        <div class="row-fluid">
            <?= $form->field($task, 'assigned_id')
                ->widget(UserSelect::className(), ['userField' => 'assigned'])
            ?>
        </div>
        <div class="row-fluid">
            <?= $form->field($task, 'priority')
                ->dropDownList(Task::priorityLabels())
            ?>
        </div>
        <?php if(count($projectService->getDifficultyList()) > 1) { ?>
            <div class="row-fluid">
                <?= $form->field($task, 'dict_difficulty_id')
                    ->dropDownList($projectService->getDifficultyList())
                ?>
            </div>
        <?php } ?>
        <?php if(count($projectService->getVersionList(true)) > 1) { ?>
            <div class="row-fluid">
                <?= $form->field($task, 'dict_version_open_id')
                    ->dropDownList($projectService->getVersionList(true))
                ?>
            </div>
        <?php } ?>
        <?php if(count($projectService->getVersionList(false)) > 1) { ?>
            <div class="row-fluid">
                <?= $form->field($task, 'dict_version_close_id')
                    ->dropDownList($projectService->getVersionList(false))
                ?>
            </div>
        <?php } ?>
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
