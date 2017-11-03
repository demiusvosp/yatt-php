<?php
/**
 * User: demius
 * Date: 30.10.17
 * Time: 22:54
 */

use app\helpers\HtmlBlock;

/** @var array $items */

$this->title = $project->name;
$this->params['breadcrumbs'][] = Yii::t('project/settings', 'Users');
$this->params['project'] = $project;
?>
<div class="row-fluid">
    <table class="table table-striped item-val">
        <tbody>
        <?php foreach ($items as $item) { ?>
            <tr>
                <td class="item">
                    <?=HtmlBlock::roleBadge($item['role']);?>
                </td>
                <td class="value">
                    <?php foreach ($item['users'] as $user) { ?>
                        <?= HtmlBlock::userItem($user) ?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>