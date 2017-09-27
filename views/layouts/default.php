<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 27.09.17
 * Time: 17:55
 */

use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<section class="content-header">
    <div class="row-fluid">
        <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
    </div>
</section>
<section class="content">
    <?= $content ?>
</section>
<?php $this->endContent(); ?>
