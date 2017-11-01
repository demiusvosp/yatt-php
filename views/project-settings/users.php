<?php
/**
 * User: demius
 * Date: 30.10.17
 * Time: 22:54
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\helpers\HtmlBlock;
use app\components\access\Role;

/** @var Role $roles */

$this->title = $project->name;
$this->params['breadcrumbs'][] = Yii::t('project/settings', 'Users');
$this->params['project'] = $project;
?>
<div class="row-fluid">
    <table class="table table-striped item-val">
        <tbody>
        <?php foreach ($roles as $role) { ?>
            <tr>
                <td class="item">
                    <?=HtmlBlock::roleBadge($role);?>
                </td>
                <td class="value">
<i>тут будет виджет юзеров</i>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>