<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\entities\Project */

$this->title = Yii::t('project', 'Update {modelClass}: ', [
    'modelClass' => 'Project',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('project', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('common', 'Update');
?>
<div class="project-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
