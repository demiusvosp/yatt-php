<?php
/**
 * User: demius
 * Date: 23.03.18
 * Time: 22:52
 */

namespace app\components\auth\templates;

use app\components\auth\Accesses;
use Yii;


/**
 * Class EmployeeView - Стандартный рабочий. Есть кто смотрит, есть, кто работает в проекте.
 *
 * @package app\components\auth\templates
 */
class EmployeeView implements IAccessesTemplate
{
    const ADMIN = 'projectAdmin';
    const EMPLOYEE = 'projectEmployee';
    const VIEW = 'projectView';


    /**
     * Получить имя шаблона полномочий
     * @return string
     */
    public static function name()
    {
        return Yii::t('access/templates', 'Admin-Employee-View');
    }


    /**
     * Получить список названий ролей
     * @return array - [roleName => roleLabel, ...]
     */
    public static function getRolesLabels()
    {
        return [
            static::ADMIN            => Yii::t('access/templates', 'Project admin'),
            static::EMPLOYEE         => Yii::t('access/templates', 'Project employee'),
            static::VIEW             => Yii::t('access/templates', 'Project watcher'),
        ];
    }


    /**
     * Получить иерархию полномочий
     * @return array - [roleName => [permissionName, ...], ...]
     */
    public static function getRolesHierarchy()
    {
        return [
            static::ADMIN => [
                Accesses::PROJECT_SETTINGS,
                static::EMPLOYEE,
                Accesses::MANAGE_COMMENT,
            ],
            static::EMPLOYEE => [
                static::VIEW,
                Accesses::OPEN_TASK,
                Accesses::EDIT_TASK,
                Accesses::CHANGE_STAGE,
                Accesses::CLOSE_TASK
            ],
            static::VIEW => [
                Accesses::PROJECT_VIEW,
                Accesses::CREATE_COMMENT
            ],
        ];
    }

}