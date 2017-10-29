<?php
/**
 * User: demius
 * Date: 28.10.17
 * Time: 0:46
 */

namespace app\helpers;


use Yii;
use app\models\entities\Project;


/**
 * Class Access - Хелпер работы с ролями и полномочиями.
 *
 * @package app\helpers
 */
class Access
{
    // глобальные роли
    const ROOT = 'root';
    const USER = 'user';

    // глобальные полномочия
    const PROJECT_MANAGEMENT = 'projectManagement';
    const USER_MANAGEMENT = 'userManagement';
    const ACCESS_MANAGEMENT = 'accessManagement';

    // роли в проекте
    const ADMIN = 'projectAdmin';
    const EMPLOYEE = 'projectEmployee';
    const VIEW = 'projectView';

    // полномочия в проекте
    const OPEN_TASK = 'openTask';
    const EDIT_TASK = 'editTask';
    const CLOSE_TASK = 'closeTask';
    const PROJECT_SETTINGS = 'projectSettings';


    /**
     * Переводы ролей и полномочий
     *
     * @return array
     */
    public static function itemLabels()
    {
        return [
            static::ROOT               => Yii::t('access', 'root'),
            static::USER               => Yii::t('access', 'user'),
            static::PROJECT_MANAGEMENT => Yii::t('access', 'Project management'),
            static::USER_MANAGEMENT    => Yii::t('access', 'User management'),
            static::ACCESS_MANAGEMENT  => Yii::t('access', 'Access management'),

            static::ADMIN            => Yii::t('access', 'Project admin'),
            static::EMPLOYEE         => Yii::t('access', 'Project Employee'),
            static::VIEW             => Yii::t('access', 'Project watcher'),
            static::OPEN_TASK        => Yii::t('access', 'can open task'),
            static::EDIT_TASK        => Yii::t('access', 'can edit task'),
            static::CLOSE_TASK       => Yii::t('access', 'can close task'),
            static::PROJECT_SETTINGS => Yii::t('access', 'can change setting'),
        ];
    }


    /**static::OPEN_TASK        => Yii::t('access', 'can open task'),
     * Глобальные роли и полномочия
     *
     * @return array
     */
    public static function globalItems()
    {
        $items = [
            static::ROOT,
            static::USER,

            static::PROJECT_MANAGEMENT,
            static::USER_MANAGEMENT,
            static::ACCESS_MANAGEMENT,
        ];

        return array_combine($items, $items);
    }


    /**
     * Роли и полномочия проекта
     *
     * @return array
     */
    public static function projectItems()
    {
        $items = [
            static::ADMIN,
            static::EMPLOYEE,
            static::VIEW,

            static::OPEN_TASK,
            static::EDIT_TASK,
            static::CLOSE_TASK,
            static::PROJECT_SETTINGS,
        ];

        return array_combine($items, $items);
    }


    /**
     * Является ли роль/полномочия ассоциированной с проектом
     *
     * @param $accessItem
     * @return bool
     */
    public static function isProjectItem($accessItem)
    {
        return isset(static::projectItems()[$accessItem]);
    }


    /**
     * Получить полное имя роли/полномочия ассоциированной с проектом
     *
     * @param string       $accessItem
     * @param Project|null $project
     * @return string
     */
    public static function projectItem($accessItem, $project = null)
    {
        if (static::isProjectItem($accessItem) && $project) {
            return $accessItem . '_' . $project->suffix;
        }

        return $accessItem;
    }

}
