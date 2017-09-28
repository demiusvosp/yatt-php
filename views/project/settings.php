<?php
/**
 * Страница проекта. Настройки.
 * User: demius
 * Date: 24.09.17
 * Time: 14:58
 */

use app\models\entities\Project;

/* @var $this yii\web\View */
/* @var $project Project */

$this->title = $project->name;
$this->params['breadcrumbs'][] = Yii::t('project', 'Settings');
$this->params['project'] = $project;

?>
<div class="row-fluid">
    <div class="box box-default box-solid">
        <div class="box-header">
            <h3 class="box-title">Справочники</h3>
        </div>
        <div class="box-body">
            здесь будет справочник, или множество их
        </div>
    </div>
</div>
