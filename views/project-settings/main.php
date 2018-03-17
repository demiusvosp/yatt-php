<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 15.10.17
 * Time: 15:14
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\helpers\HtmlBlock;


/**
 * @var $project
 */

$this->title = HtmlBlock::titleString(
    Yii::t('project', 'Settings') . ' - ' . Yii::t('project/settings', 'Main'),
    $project
);
$this->params['breadcrumbs'][] = Yii::t('project/settings', 'Main');
$this->params['project'] = $project;
?>
<div class="row-fluid">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($project, 'description')->editor(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('common', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
