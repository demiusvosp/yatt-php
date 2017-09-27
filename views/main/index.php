<?php

/* @var $this yii\web\View */
/* @var $projects */

$this->title = Yii::$app->name;
?>
<div class="row-fluid">

    <?php foreach ($projects as $project) { ?>
        <?= $this->render('//project/item', ['project' => $project]); // когда логика вывода тайла станет сложнее, стоит переделать в виджет ?>
    <?php } ?>

</div>
