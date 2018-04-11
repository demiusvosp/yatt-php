<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 19:19
 */

namespace app\components\auth;

use Yii;
use app\models\entities\Project;


/**
 * Class Permission - обертка над yii\rbac\Permission, позволяющая сохранить в ней дополнительную необходимую инфу.
 * Не модель!
 *
 * @package Yatt\access
 */
class Permission extends \yii\rbac\Permission implements IAccessItem
{
    const TYPE_GLOBAL = 0;
    const TYPE_PROJECT = 1;

    const BUILT_IN = 2;
    const CUSTOM = 3;

    // глобальные полномочия
    const MANAGEMENT_PROJECT = 'managementProject';
    const MANAGEMENT_USER = 'managementUser';
    const MANAGEMENT_ACCESS = 'managementAccess';

    // полномочия в проекте
    const PROJECT_SETTINGS = 'projectSettings';
    const PROJECT_VIEW = 'projectView';

    const OPEN_TASK = 'openTask';
    const EDIT_TASK = 'editTask';
    const CHANGE_STAGE = 'changeStage';
    const CLOSE_TASK = 'closeTask';

    const CREATE_COMMENT = 'createComment'; // создавать и редактировать свои коменты
    const MANAGE_COMMENT = 'manageComment'; // редактировать чужие коменты


    use TAccessItem;


    /**
     * Переводы ролей и полномочий
     *
     * @return array
     */
    public static function itemLabels()
    {
        return [
            static::MANAGEMENT_PROJECT => Yii::t('access/items', 'Project management'),
            static::MANAGEMENT_USER    => Yii::t('access/items', 'User management'),
            static::MANAGEMENT_ACCESS  => Yii::t('access/items', 'Accesses management'),

            static::PROJECT_SETTINGS => Yii::t('access/items', 'can change setting'),
            static::PROJECT_VIEW     => Yii::t('access/items', 'can see project'),
            static::OPEN_TASK        => Yii::t('access/items', 'can open task'),
            static::EDIT_TASK        => Yii::t('access/items', 'can edit task'),
            static::CHANGE_STAGE     => Yii::t('access/items', 'can change task stage'),
            static::CLOSE_TASK       => Yii::t('access/items', 'can close task'),
            static::CREATE_COMMENT   => Yii::t('access/items', 'can create comment'),
            static::MANAGE_COMMENT   => Yii::t('access/items', 'can manage (edit/delete) comment'),
        ];
    }


    public static function getProjectPermissions()
    {
        return [
            static::PROJECT_SETTINGS,
            static::PROJECT_VIEW,

            static::OPEN_TASK,
            static::EDIT_TASK,
            static::CHANGE_STAGE,
            static::CLOSE_TASK,

            static::CREATE_COMMENT,
            static::MANAGE_COMMENT,
        ];
    }


    /**
     * Получить полное имя полномочия
     *
     * @param         $name
     * @param Project $project
     * @return string
     */
    public static function getFullName($name, $project)
    {
        if(Accesses::isGlobal($name)) {
            // это глобальное полномочие
            return $name;
        }

        if(strpos($name, '_') !== false) {
            // уже полное имя
            return $name;
        }

        if(in_array($name, static::getProjectPermissions())) {
            // полномочие проекта
            if($project instanceof Project) {
                $project = $project->suffix;
            }
            if (is_string($project)) {
                return $project . '_' . $name;
            } else {
                    throw new \InvalidArgumentException('Project permission '.$name.' without project');
            }
        }

        // глобальное полномочие
        return $name;
    }


    /**
     * Проверить является ои элемент доступа относящимся к проекту
     *
     * @param $name
     * @return bool
     */
    public static function isProjectItem($name)
    {
        if(Accesses::isGlobal($name)) {
            // это глобальная роль
            return false;
        }

        if(strpos($name, '_') !== false) {
            // Да в имени есть проект
            return true;
        }

        return false;
    }


    public static function isPermission($item)
    {

    }
}
