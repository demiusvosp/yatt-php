<?php

/* @var $this yii\web\View */
/* @var $projects */

// если убрать этот блок, в его копии в верстке перестанет находить $project (вероятно и там он отсюда, а не из тамошнего цикла)
foreach ($projects as $project) {
    //var_dump($project);
}

$this->title = Yii::$app->name;
?>
<div class="site-index">


    <div class="body-content">

        <? foreach ($projects as $project) { ?>
            <?= $this->render('//project/item', ['project' => $project]) ?>
        <? } ?>

    </div>
</div>
