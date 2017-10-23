<?php
/**
 * User: demius
 * Date: 23.10.17
 * Time: 22:19
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\models\entities\Task;
use app\models\forms\CloseTaskForm;
use app\helpers\ProjectUrl;

/** @var Task $task */
/** @var CloseTaskForm $model */
/** @var string $modalId */
?>
<div class="modal fade" id="<?= $modalId ?>" style="display: none;">
    <div class="modal-dialog close-task-dialog">
        <div class="modal-content box box-solid box-success">
            <div class="box-header">
                <h4 class="box-title">Закрыть задачу?</h4>
                <div class="box-tools pull-right">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <?php $form = ActiveForm::begin(['action' => ProjectUrl::to(['close']), 'id'=> 'closeTaskForm']); ?>
            <div class="modal-body box-body">
                <?= Html::activeHiddenInput($model, 'index') ?>
                <?= Html::activeHiddenInput($model, 'suffix') ?>

                <?= $form->field($model, 'close_reason')->dropDownList(Task::reasonLabels()); ?>
            </div>
            <div class="modal-footer box-footer">
                <?= Html::submitButton(
                        '<i class="glyphicon glyphicon-ok"></i> Закрыть',
                        ['class' => 'btn btn-success']) ?>
<!--                <button type="button" class="btn btn-success" id="goClose">Закрыть</button>-->
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>