<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 11.10.17
 * Time: 21:24
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\helpers\ProjectUrl;
use app\models\entities\DictBase;
use app\models\forms\DictsWidgetForm;
use app\models\entities\Project;

/* @var $dictForm DictsWidgetForm */
/* @var $dict string */
/* @var $project Project|null */
/* @var $dictItemView string */
/* @var $inputPrefix string */

require_once ($dictItemView.'.php');
?>
<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
<table
    id="dictForm"
    class="table dict-form"
    data-dict-url="<?= ProjectUrl::toDictAction($project) ?>"
    data-dict="<?= $dictForm->itemClass ?>"
    data-dict-name="<?=$inputPrefix?>"
    <?= $project ? ('data-project="'.$project->suffix.'"') : '' ?>
>
    <thead>
    <tr>
        <th><?=Yii::t('dicts', '#')?></th>
        <?php (function_exists('columnHeaders'))?columnHeaders():'' ?>
        <th><?=Yii::t('dicts', 'Assigned tasks')?></th>
        <th class="ctrl-column">&nbsp;</th>
    </tr>
    </thead>
    <tbody class="ui-sortable">
    <?php $fieldSettings = ['template' => '{input}{error}', 'options' => ['class' => '']]; ?>
    <?php foreach ($dictForm->items as $index => $item) { ?>
        <?php /* @var $item DictBase */ ?>
        <tr class="dict_item" id="<?= $index ?>" data-id="<?= $item->id ?>">
            <td><?=$index?></td>
            <?php (function_exists('columnRow'))?columnRow($form, $item, $index, $fieldSettings):''; ?>
            <td class="centered">
                <?=$item->countTask()?>
            </td>
            <td class="centered ctrl-column">
                <?= Html::activeHiddenInput($item, "[$index]position"); ?>
                <?=(function_exists('columnCtrl'))?columnCtrl($form, $item):''; ?>

                <span class="btn btn-flat <?=$item->disableDelete() ? 'disabled' : 'drop-item'?>">
                    <i class="fa fa-close text-red"></i>
                </span>
                <span class="btn btn-default <?=$item->disableReposition() ? 'disabled' : ''?>">
                    <i class="fa fa-arrows-v"></i>
                </span>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
