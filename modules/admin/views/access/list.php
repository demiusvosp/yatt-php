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

$this->title = Yii::t('access', 'Access management');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-solid box-default"><!-- box-solid box-default альтернатива-->
    <div class="box-header">
        <h1 class="box-title"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="box-body">
        <ul class="table bottom-hr">
        <?php foreach ($list as $key => $item) { ?>
            <li>
                <b><?= isset($item['label']) ? ($item['label'].':') : '-' ?></b>
                <?php if(isset($item['items'])) { ?>
                    <table class="table table-striped item-val" id="<?=$key?>">
                    <tbody>
                        <?php foreach ($item['items'] as $role) { ?>
                            <tr>
                                <td class="item">
                                    <?=HtmlBlock::roleBadge($role);?>
                                </td>
                                <td class="value">
<i>тут будет виджет юзеров</i>
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
