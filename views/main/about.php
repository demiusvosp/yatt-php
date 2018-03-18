<?php

/* @var $this yii\web\View */

use yii\helpers\Markdown;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">

    <?= Markdown::process(file_get_contents(Yii::getAlias('@app/README.md')), 'gfm') ?>

</div>
