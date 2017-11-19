<?php
/**
 * User: demius
 * Date: 19.11.17
 * Time: 11:28
 */

use app\models\entities\Project;


/* @var $project Project */
/* @var $caption string */
/* @var $link string|false */
/* @var $options array */

?>
<div class="<?= $options['class'] ?>"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <?php if ($link) { ?>
        <a href="<?= $link ?>">
            <?php } ?>
            <h3 class="box-title">
                <?= $caption ?>
            </h3>
            <?php if ($link) { ?>
        </a>
    <?php } ?>
    </div>
    <div class="box-body">
        <p>Здесь будет задачи, статистика и проч...</p>
    </div>
</div>