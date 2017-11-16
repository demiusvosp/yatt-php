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
/* @var $dictItemView string */

require_once ($dictItemView.'.php');
?>
<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
<table
    id="dictForm"
    class="table"
    data-drop-url="<?= Url::to(['dict/delete-item']) ?>"
    data-dict="<?= $dictForm->tableName()?>"
    <?= $project ? ('data-project="'.$project->id.'"') : '' ?>
>
    <thead>
    <tr>
        <th><?=Yii::t('dicts', '#')?></th>
        <?= columnHeaders() ?>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody class="ui-sortable">
    <?php $fieldSettings = ['template' => '{input}{error}', 'options' => ['class' => '']]; ?>
    <?php foreach ($dictForm->items as $index => $item) { ?>
        <?php /* @var $item ActiveRecord */ ?>
        <tr class="dict_item" id="<?= $index ?>" data-id="<?= $item->id ?>">
            <td><?=$index?></td>
            <?= columnRow($form, $item, $index, $fieldSettings); ?>
            <td>
                <?= Html::activeHiddenInput($item, "[$index]position"); ?>
                <span class="btn btn-flat drop-item">
                    <i class="fa fa-close text-red"></i>
                </span>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
