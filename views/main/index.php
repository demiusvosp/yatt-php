<?php

use app\widgets\ProjectTileWidget;

/* @var $this yii\web\View */
/* @var $projects */

$this->title = Yii::$app->name;
?>
<div class="row-fluid project-tiles">
    <?php foreach ($projects as $project) { ?>
        <div class="item">
            <?= ProjectTileWidget::widget(['project' => $project]) ?>
        </div>
    <?php } ?>

</div>
