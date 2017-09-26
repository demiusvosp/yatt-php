<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 26.09.17
 * Time: 19:32
 */
use yii\helpers\Html;
use yii\grid\GridView;

use app\models\entities\Project;
use app\models\entities\Task;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('task', 'Task list');
$this->params['breadcrumbs'][] = $this->title;
?>
список задач