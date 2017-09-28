<?php
/**
 * Страница проекта. Настройки.
 * User: demius
 * Date: 24.09.17
 * Time: 14:58
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\entities\Project;
use app\models\forms\DictStatesForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $project Project */
/* @var $dictStatesForm DictStatesForm */

$this->title = $project->name;
$this->params['breadcrumbs'][] = Yii::t('project', 'Settings');
$this->params['project'] = $project;

?>
<div class="row-fluid">
    <div class="box box-default box-solid dict_states-block">
        <div class="box-header">
            <h3 class="box-title">Состояния задачи</h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin();
                $form->enableClientValidation = false;
            ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th><?=Yii::t('dicts', '#')?></th>
                            <th><?=Yii::t('dicts', 'Name')?></th>
                            <th><?=Yii::t('dicts', 'Description')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $fieldSettings = ['template' => '{input}{error}', 'options' => ['class' => '']]; ?>
                        <?php foreach ($dictStatesForm->states as $index => $state) { ?>
                            <tr>
                                <td><?=$index?></td>
                                <td><?= $form->field($state, "[$index]name", $fieldSettings)->textInput(); ?></td>
                                <td><?= $form->field($state, "[$index]description", $fieldSettings)->textInput(); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?= Html::submitButton(Yii::t('dicts', 'Save'), ['class' => 'btn btn-primary']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
