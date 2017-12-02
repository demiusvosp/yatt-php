<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $project app\models\entities\Project */

$this->title = Yii::$app->name . ' :: ' . Yii::t('project', 'Create Project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('project', 'Project Manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('project', 'Create Project');
?>
<div class="box box-solid box-default"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <h1 class="box-title"><?= Html::encode(Yii::t('project', 'Create Project')) ?></h1>
    </div>

    <div class="box-body">
        <?= $this->render('_form', [
            'project' => $project,
        ]) ?>
    </div>
</div>
