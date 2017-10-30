<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 19:48
 */

namespace app\helpers;


use yii\helpers\Html;
use app\models\entities\Project;
use app\components\access\Role;


/**
 * Class HtmlBlock - всякие мелкие html-блоки, коим место в шаблонах, но по по идеологии Yii это хелперы.
 * Будем переходить на твиг, перепилим нормально
 *
 * @package app\helpers
 */
class HtmlBlock
{

    /**
     * Значок отношения к проекту
     *
     * @param Project $project
     * @return string
     */
    public static function projectBadge($project)
    {
        return Html::a(
            $project->name,
            ProjectUrl::to(['/admin/project/view', 'id' => $project->id]),
            ['class' => 'sign sign-project']
        );
    }


    /**
     * @param Role $role
     * @return string
     */
    public static function roleBadge($role)
    {
        return Html::a(
            $role->label,
            '',
            ['class' => 'sign ' . ($role->isGlobal() ? 'sign-global-role' : 'sign-project-role')]
        );
    }
}