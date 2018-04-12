<?php
/**
 * User: demius
 * Date: 19.11.17
 * Time: 11:28
 */

use app\models\entities\Project;
use app\models\entities\Task;
use app\helpers\HtmlBlock;
use app\helpers\ProjectUrl;


/* @var $project Project */
/* @var $caption string */
/* @var $link string|false */
/* @var $options array */
/* @var $taskStat array */
/* @var $lastTasks array */

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
        <div class="task-stat">
            <div class="stat-item">Открытых задач <b><?=$taskStat['open']?></b> из <b><?=$taskStat['total']?></b>.</div>
            <div class="stat-item"><b>Прогресс: </b><?=HtmlBlock::progressWidget($taskStat['progress'])?></div>
            <?php if(count($taskStat['versions']) > 0) { ?>
                <div class="stat-item">
                    <b>Список версий:</b>
                    <table class="table">
                        <tbody>
                        <?php foreach ($taskStat['versions'] as $version) { ?>
                            <tr class="<?=$version['type']?>">
                                <td><?=$version['name']?></td>
                                <td><?=HtmlBlock::progressWidget($version['progress'])?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
        <?php if($lastTasks) { ?>
            <div class="last-tasks">
                <b>Последние задачи:</b>
                <ul>
                    <?php
                        /* @var Task $task */
                        foreach ($lastTasks as $task) {
                    ?>
                        <li>
                            <a href="<?=ProjectUrl::toTask($task)?>">
                                <?=$task->name . ' - ' . $task->caption ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
    </div>
</div>