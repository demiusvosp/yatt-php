<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\entities\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('user', 'User Manager');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-solid box-default"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <h1 class="box-title"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="box-body">
        <p>
            <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                [
                    'attribute' => 'username',
                    //'label'  => Yii::t('user', 'ID'),
                    'content' => function($user) {
                        return Html::a(
                            $user->username,
                            ['view', 'id' => $user->id]
                        );
                    },
                ],
                'email:email',

                'created_at:datetime',
                'updated_at:datetime',
                [
                    'attribute' => 'status',
                    'content' => function($user) {
                        /** @var User $user */
                        return User::getStatusesArray()[$user->status];
                    },
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
