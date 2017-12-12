<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 11.10.17
 * Time: 21:24
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\helpers\ProjectUrl;
use app\models\entities\DictBase;
use app\models\forms\DictsWidgetForm;

/* @var $dictForm DictsWidgetForm */
/* @var $dictItemView string */

if(!isset($dictItemView)) {
    $dictItemView = 'dictDefaultModel';
}
$project = $dictForm->project;

require_once ($dictItemView.'.php');
?>
<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
<table
    id="dictForm"
    class="table dict-form"
    data-dict-url="<?= ProjectUrl::toDictAction($project) ?>"
    data-dict="<?= $dictForm->itemClass ?>"
    data-dict-name="<?= strtolower(end($dictForm->items)->formName())?>"
    <?= $project ? ('data-project="'.$project->suffix.'"') : '' ?>
>
    <thead>
    <tr>
        <th><?=Yii::t('dicts', '#')?></th>
        <?php (function_exists('columnHeaders'))?columnHeaders():'' ?>
        <th><?=Yii::t('dicts', 'Assigned entities')?></th>
        <th><?=Yii::t('dicts', 'Actions')?></th>
    </tr>
    </thead>
    <tbody class="ui-sortable">
    <?php $fieldSettings = ['template' => '{input}{error}', 'options' => ['class' => '']]; ?>
    <?php foreach ($dictForm->items as $index => $item) { ?>
        <?php /* @var $item DictBase */
            $taskCount = ArrayHelper::getValue($dictForm->relatedItemCount, $item->id, 0);
        ?>
        <tr class="dict_item" id="<?= $index ?>" data-id="<?= $item->id ?>">
            <td><?=$index?></td>
            <?php (function_exists('columnRow'))?columnRow($form, $item, $index, $fieldSettings):''; ?>
            <td class="centered">
                <?=$taskCount; ?>
            </td>
            <td class="centered ctrl-column">
                <?= Html::activeHiddenInput($item, "[$index]position"); ?>
                <?php (function_exists('columnCtrl'))?columnCtrl($form, $item):''; ?>

                <?php if(! $item->isNewRecord) { ?>
                    <span
                        class="btn btn-flat <?=$item->disableDelete() || ($taskCount > 0) ? 'disabled' : 'drop-item'?>"
                        title="<?=Yii::t('dicts',
                            ($item->disableDelete())?
                                'Cannot delete this dict item':
                                (($taskCount > 0)?
                                    'Dict item associated to entities':
                                    'Delete dict item'
                                )
                            )?>"
                    >
                        <i class="fa fa-close text-red"></i>
                    </span>
                <?php } ?>
                <span
                    class="btn btn-default <?=$item->disableReposition() ? 'disabled' : ''?>"
                    title="<?=Yii::t('dicts',
                        ($item->disableReposition()) ?
                            'Cannot reposition this dict item' :
                            'Drag and drop to new position'
                        )?>"
                >
                    <i class="fa fa-arrows-v"></i>
                </span>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
