<?php
/**
 * User: demius
 * Date: 28.10.17
 * Time: 0:46
 */

namespace app\components\auth;


use Yii;
use app\models\entities\Project;


/**
 * Class Accesses - Набор предустановленных ролей и полномочий
 *
 * @package app\helpers
 */
class Accesses
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
    const CHANGE_STAGE = 'changeStage';
    const CLOSE_TASK = 'closeTask';
    const PROJECT_SETTINGS = 'projectSettings';
    const CREATE_COMMENT = 'createComment';
    const MANAGE_COMMENT = 'manageComment'; // управление комментариями (редактирование/удаление)


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
            static::ACCESS_MANAGEMENT  => Yii::t('access', 'Accesses management'),

            static::ADMIN            => Yii::t('access', 'Project admin'),
            static::EMPLOYEE         => Yii::t('access', 'Project Employee'),
            static::VIEW             => Yii::t('access', 'Project watcher'),
            static::OPEN_TASK        => Yii::t('access', 'can open task'),
            static::EDIT_TASK        => Yii::t('access', 'can edit task'),
            static::CHANGE_STAGE     => Yii::t('access', 'can change task stage'),
            static::CLOSE_TASK       => Yii::t('access', 'can close task'),
            static::PROJECT_SETTINGS => Yii::t('access', 'can change setting'),
            static::CREATE_COMMENT => Yii::t('access', 'can create comment'),
            static::MANAGE_COMMENT => Yii::t('access', 'can manage (edit/delete) comment'),
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
            static::CHANGE_STAGE,
            static::CLOSE_TASK,
            static::PROJECT_SETTINGS,
            static::CREATE_COMMENT,
            static::MANAGE_COMMENT,
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
        if (strpos('_', $accessItem) !== false) {
            // в роли/полномочии уже указан проект
            list($accessItem, $project) = explode('_', $accessItem);

            if (!$project) {
                throw new \InvalidArgumentException('project access item without project');
            }
        }

        return isset(static::projectItems()[$accessItem]);
    }


    /**
     * Получить полное имя роли/полномочия ассоциированной с проектом
     *
     * @param string              $accessItem
     * @param Project|string|null $project
     * @return string
     */
    public static function projectItem($accessItem, $project = null)
    {
        if (strpos('_', $accessItem) !== false) {
            // роль/полномочие уже скомбинированы с проектом
            return $accessItem;
        }

        if (static::isProjectItem($accessItem)) {
            if(!$project) {
                // если эта единица полномочий относится к проекту, он должен быть передан
                throw new \DomainException('Access item ' . $accessItem . ' only within project');
            }

            if ($project instanceof Project) {
                $project = $project->suffix;
            }

            return $accessItem . '_' . $project;
        }

        return $accessItem;
    }

}