<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 11.10.17
 * Time: 21:24
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\db\ActiveRecord;
use app\models\forms\DictForm;
use app\models\entities\Project;

/* @var $dictForm DictForm */
/* @var $dict string */
/* @var $project Project|null */
?>
<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
<table
    id="dictForm"
    class="table ui-sortable"
    data-drop-url="<?= Url::to(['dict/delete-item']) ?>"
    data-dict="<?= $dictForm->tableName()?>"
    <?= $project ? ('data-project="'.$project->id.'"') : '' ?>
>
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
        <?php /* @var $item ActiveRecord */ ?>
        <tr class="dict_item" data-id="<?= $item->id ?>">
            <td><?=$index?></td>
            <td><?= $form->field($item, "[$index]name", $fieldSettings)->textInput(); ?></td>
            <td><?= $form->field($item, "[$index]description", $fieldSettings)->textInput(); ?></td>
            <td>
                <span class="btn btn-flat drop-item">
                    <i class="fa fa-close text-red"></i>
                </span>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?= Html::submitButton(Yii::t('dicts', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
