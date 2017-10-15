<?php
/**
 * Страница проекта. Настройки.
 * User: demius
 * Date: 24.09.17
 * Time: 14:58
 */

use app\models\entities\Project;
use app\models\forms\DictForm;

use app\widgets\DictEdit;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $project Project */
/* @var $dictForm DictForm */

$this->title = $project->name;
$this->params['breadcrumbs'][] = Yii::t('project/settings', 'Types');
$this->params['project'] = $project;

?>
<div class="row-fluid">
    <div class="box box-default box-solid dict_states-block">
        <div class="box-header">
            <h3 class="box-title"><?=Yii::t('dicts', 'Tasks types')?></h3>
        </div>
        <div class="box-body">
            <?= DictEdit::widget(['dictForm' => $dictForm]); ?>
        </div>
    </div>
</div>
