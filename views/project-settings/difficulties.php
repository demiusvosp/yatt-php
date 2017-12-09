<?php
/**
 * Страница проекта. Настройки.
 * User: demius
 * Date: 24.09.17
 * Time: 14:58
 */

use app\models\entities\Project;
use app\models\forms\DictsWidgetForm;
use app\helpers\HtmlBlock;
use app\widgets\DictEdit;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $project Project */
/* @var $dictForm DictsWidgetForm */

$this->title = HtmlBlock::titleString(
    Yii::t('project', 'Settings') . ' - ' . Yii::t('project/settings', 'Difficulty levels'),
    $project
);
$this->params['breadcrumbs'][] = Yii::t('project/settings', 'Difficulty levels');
$this->params['project'] = $project;

?>
<div class="row-fluid">
    <div class="box box-default box-solid dict_setting_block">
        <div class="box-header">
            <h3 class="box-title"><?=Yii::t('dicts', 'Difficulty levels')?></h3>
        </div>
        <div class="box-body">
            <?= DictEdit::widget(['dictForm' => $dictForm, 'dictItemView' => 'dictDifficulty']); ?>
        </div>
    </div>
</div>
