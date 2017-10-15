<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\entities\Project */

$this->title = Yii::t('project', 'Create Project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('project', 'Project Manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-solid box-default"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <h1 class="box-title"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
