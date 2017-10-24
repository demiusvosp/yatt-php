<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\entities\User;
use app\helpers\ProjectUrl;

/* @var $this yii\web\View */
/* @var $user app\models\entities\User */

$this->title = $user->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-solid box-default"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <h1 class="box-title"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="box-body">
        <p>
            <?= Html::a(Yii::t('common', 'Update'), ['update', 'id' => $user->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('common', 'Delete'), ['delete', 'id' => $user->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $user,
            'attributes' => [
                'id',
                'username',
                'email:email',
                'created_at:datetime',
                'updated_at:datetime',
                [
                    'attribute' => 'status',
                    'value' => function($user) {
                        /** @var User $user */
                        return User::getStatusesArray()[$user->status];
                    }
                ],
                [
                    'attribute' => 'projects',
                    'label' => Yii::t('user', 'Admin in projects'),
                    'format' => 'raw',
                    'value' => function($user) {
                        /** @var User $user */
                        $projects = '';
                        foreach ($user->projects as $project) {
                            $projects .=
                                Html::a($project->name, ProjectUrl::to(['/admin/project/view', 'id' => $project->id])) . ', ';
                        }
                        return $projects;
                    }
                ],
            ],
        ]) ?>
    </div>
</div>

