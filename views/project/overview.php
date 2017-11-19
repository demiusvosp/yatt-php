<?php
/**
 * Страница проекта. Общая информация
 * User: demius
 * Date: 24.09.17
 * Time: 14:58
 */

use app\models\entities\Project;
use app\widgets\ProjectTileWidget;


/* @var $this yii\web\View */
/* @var $project Project */

$this->title                   = $project->name;
$this->params['breadcrumbs'][] = Yii::t('project', 'Overview');
$this->params['project']       = $project;

?>
<div class="row-fluid">
    <div class="col-md-5">
        <?= ProjectTileWidget::widget([
            'project' => $project,
            'link'    => false,// пока нет отдельной страницы со статистикой, графиками и проч.
            'caption' => Yii::t('project', 'Statistics'),
            'options' => [
                'class' => 'box-success'
            ],
        ]) ?>
    </div>
    <div class="col-md-7">
        <div class="box box-success"><!-- box-solid box-default альтернатива-->
            <div class="box-header">
                <h3 class="box-title">
                    <?= $project->getAttributeLabel('description') ?>
                </h3>
            </div>
            <div class="box-body">
                <?= $project->description ?>
            </div>
        </div>
    </div>
</div>
