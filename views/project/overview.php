<?php
/**
 * Страница проекта. Общая информация
 * User: demius
 * Date: 24.09.17
 * Time: 14:58
 */

use yii\helpers\Html;
use app\models\entities\Project;

/* @var $this yii\web\View */
/* @var $project Project */

$this->title = $project->name;
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?=$project->name ?></h1>

<ul class="nav nav-tabs">
    <li class="active">
        <a href="#"><?=Yii::t('project', 'Overview')?></a>
    </li>
</ul>

<div class="well"><?=$project->description?></div>