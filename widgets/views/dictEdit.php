<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 11.10.17
 * Time: 21:24
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\forms\DictForm;

/* @var $dictForm DictForm */
?>
<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
<table class="table dictForm ui-sortable">
    <thead class="ui-state-disabled">
    <tr>
        <th><?=Yii::t('dicts', '#')?></th>
        <th><?=Yii::t('dicts', 'Name')?></th>
        <th><?=Yii::t('dicts', 'Description')?></th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php $fieldSettings = ['template' => '{input}{error}', 'options' => ['class' => '']]; ?>
    <?php foreach ($dictForm->items as $index => $item) { ?>
        <tr>
            <td><?=$index?></td>
            <td><?= $form->field($item, "[$index]name", $fieldSettings)->textInput(); ?></td>
            <td><?= $form->field($item, "[$index]description", $fieldSettings)->textInput(); ?></td>
            <td><span data-id="<?=$index?>" class="btn btn-flat drop-item"><i class="fa fa-close text-red"</span>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?= Html::submitButton(Yii::t('dicts', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
