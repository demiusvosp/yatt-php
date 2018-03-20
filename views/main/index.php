<?php

use app\widgets\ProjectTile;

/* @var $this yii\web\View */
/* @var $projects */

$this->title = Yii::$app->name;
?>
<div class="row-fluid project-tiles">
    <?php foreach ($projects as $project) { ?>
        <div class="item">
            <?= ProjectTile::widget([
                'project' => $project,
                'closedVersionNum' => 4
            ]) ?>
        </div>
    <?php } ?>

</div>
