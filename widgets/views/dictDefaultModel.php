<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 17.10.17
 * Time: 22:14
 */

use yii\bootstrap\ActiveForm;
use yii\db\ActiveRecord;

?>
<?php function columnHeaders() { ?>
    <th><?=Yii::t('dicts', 'Name')?></th>
    <th><?=Yii::t('dicts', 'Description')?></th>
<?php } ?>

<?php function columnRow($form, $item, $index, $fieldSettings) {
    /** @var ActiveForm $form */
    /** @var $item ActiveRecord */
    /** @var $fieldSettings array */
?>
    <td><?= $form->field($item, "[$index]name", $fieldSettings)->textInput(); ?></td>
    <td><?= $form->field($item, "[$index]description", $fieldSettings)->textInput(); ?></td>
<?php } ?>
