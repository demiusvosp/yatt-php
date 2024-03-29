<?php
/**
 * User: demius
 * Date: 30.10.17
 * Time: 22:54
 */

use yii\helpers\Url;
use app\helpers\HtmlBlock;
use app\widgets\UserSelect;
use app\models\entities\Project;

/** @var array $items */
/** @var Project $project */

$this->title = HtmlBlock::titleString(
    Yii::t('project', 'Settings') . ' - ' . Yii::t('project/settings', 'Users'),
    $project
);
$this->params['breadcrumbs'][] = Yii::t('project/settings', 'Users');
$this->params['project'] = $project;
?>
<div class="row-fluid">
    <table
        class="table table-striped item-val user-accesses"
        data-add-url="<?=Url::to(['access/assign']) ?>"
        data-remove-url="<?=Url::to(['access/revoke']) ?>"
        data-project="<?=$project->suffix?>"
    >
        <tbody>
        <?php foreach ($items as $item) { ?>
            <tr>
                <td class="item">
                    <?=HtmlBlock::roleBadge($item['role']);?>
                </td>
                <td class="value" data-role="<?= $item['role']->name ?>">
                    <?php foreach ($item['users'] as $i => $user) { ?>
                        <?php $disabled = false;/* тут стоило бы проверить, что это роль админа,
                            и из неё нельзя удалить последнего пользователя если ту не root конечно,
                            но пусть уж стреляют себе в ногу, если хотят. Root поправит. */ ?>
                        <div class="user_wrapper" data-id="<?=$user->id ?>">
                            <?= HtmlBlock::userItem($user, $disabled) ?>
                            <?php if(!$disabled) { ?>
                                <div class="remove_user btn btn-default"><i class="fa fa-minus"></i></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <div class="add_user btn btn-default"><i class="fa fa-plus"></i></div>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="user_select unshow"><!-- class="hidden" -->
        <?= UserSelect::widget(['name' => 'user_select']) ?>
    </div>
</div>