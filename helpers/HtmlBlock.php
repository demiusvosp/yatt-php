<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 19:48
 */

namespace app\helpers;


use Yii;
use app\models\entities\User;
use yii\helpers\Html;
use app\models\entities\Project;
use app\components\auth\Role;


/**
 * Class HtmlBlock - всякие мелкие html-блоки, коим место в шаблонах, но по по идеологии Yii это хелперы.
 * Будем переходить на твиг, перепилим нормально
 *
 * @package app\helpers
 */
class HtmlBlock
{

    /**
     * @param string $title
     * @param Project|null $project
     * @return string
     */
    public static function titleString($title, $project = null)
    {
        if($project) {
            return $project->name . ' :: ' . $title;
        } else {
            return $title;
        }
    }


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
     * Значок роли юзера
     *
     * @param Role $role
     * @return string
     */
    public static function roleBadge($role)
    {
        return Html::a(
            $role->description,
            '',
            ['class' => 'sign ' . ($role->isGlobal() ? 'sign-global-role' : 'sign-project-role')]
        );
    }


    /**
     * Блок юзера
     * @param array|User $user
     * @param bool $disabled
     * @return string
     */
    public static function userItem($user, $disabled = false)
    {
        if(is_array($user)) {
            $username = $user['username'];
        } else if($user instanceof User) {
            $username = $user->username;
        } else {
            return Yii::t('common', 'Not set');
        }

        return '<div class="user-item'.($disabled?' disabled':'').'"><i class="fa fa-user"></i>&nbsp;'.$username.'</div>';
    }


    /**
     * Select2 форматирует юзеров исключительно через JS
     * @return string
     */
    public static function userItemJs()
    {
        return <<<JS
    var userItemJs = function(item) {
        return '<div class="user-item"><i class="fa fa-user"></i>&nbsp;'+item.text+'</div>';
    };
JS;

    }

    public static function progressWidget($progress, $class = '')
    {
        return '
        <div class="progress ' . $class . '">
            <div
                    class="progress-bar progress-bar-green" role="progressbar"
                    aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%"
            >
                <span class="sr-only">' . $progress . ' Complete</span>
            </div>
            <div class="progress-value">' . $progress . '%</div>
        </div>
        ';
    }
}