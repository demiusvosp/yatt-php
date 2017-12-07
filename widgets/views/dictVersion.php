<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 17.10.17
 * Time: 22:14
 */

use yii\bootstrap\ActiveForm;
use app\models\entities\DictVersion;

?>
<?php function columnHeaders() { ?>
    <th><?=Yii::t('dicts', 'Name')?></th>
    <th><?=Yii::t('dicts', 'Description')?></th>
    <th><?=Yii::t('dicts', 'Type')?></th>
    <th><?=Yii::t('dicts', 'Not closed tasks')?></th>
<?php } ?>

<?php function columnRow($form, $item, $index, $fieldSettings) {
    /** @var ActiveForm $form */
    /** @var $item DictVersion */
    /** @var $fieldSettings array */
?>
    <td><?= $form->field($item, "[$index]name", $fieldSettings)->textInput(); ?></td>
    <td><?= $form->field($item, "[$index]description", $fieldSettings)->textInput(); ?></td>
    <td><?= $form->field($item, "[$index]type", $fieldSettings)->dropDownList($item->typesAvailable()); ?></td>
    <td class="centered"><?=$item->countOpenTasks()?></td>
<?php } ?>

<?php function columnCtrl($form, $item) {
    /** @var ActiveForm $form */
    /** @var $item DictVersion */
?>
    <?php if(!$item->isNewRecord && $item->canChangeType(DictVersion::PAST)) { ?>
        <span class="btn btn-flat past-item">
            <i class="fa  fa-thumbs-o-up"></i>
        </span>
    <?php } ?>
<?php } ?>
