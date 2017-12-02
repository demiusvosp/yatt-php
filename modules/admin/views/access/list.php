<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 22:15
 */

use yii\helpers\Html;
use app\helpers\HtmlBlock;

/* @var $this yii\web\View */
/** @var array $list */

$this->title = Yii::$app->name . ' :: ' . Yii::t('access', 'Access management');
$this->params['breadcrumbs'][] = Yii::t('access', 'Access management');
?>
<div class="box box-solid box-default"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <h1 class="box-title"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="box-body">
        <ul class="table bottom-hr">
        <?php foreach ($list as $key => $block) { ?>
            <li>
                <b><?= isset($block['label']) ? ($block['label'].':') : '-' ?></b>
                <?php if(isset($block['items'])) { ?>
                    <table class="table table-striped item-val" id="<?=$key?>">
                    <tbody>
                        <?php foreach ($block['items'] as $item) { ?>
                            <tr>
                                <td class="item">
                                    <?=HtmlBlock::roleBadge($item['role']);?>
                                </td>
                                <td class="value">
                                    <?php foreach ($item['users'] as $i => $user) { ?>
                                        <?= HtmlBlock::userItem($user); ?>
                                        <?= ($i+1 < count($item['users'])) ? ', ' : '' ?>
                                    <?php } ?>
                                </td>
                            </tr>
                    <?php } ?>
                    </table>
                <?php } ?>
            </li>
        <?php } ?>
        </ul>
    </div>
</div>
