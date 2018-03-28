<?php
/**
 * User: demius
 * Date: 28.03.18
 * Time: 22:00
 */

namespace app\components\auth\templates;

use Yii;
use app\components\auth\Accesses;


/**
 * Class PersonalProject - Персональный проект. Проект досутп к которому имеет только его владелец.
 *
 * @package app\components\auth\templates
 */
class PersonalProject implements IAccessesTemplate
{
    const OWNER = 'projectAdmin';

    /**
     * Получить имя шаблона полномочий
     * @return string
     */
    public static function name()
    {
        return Yii::t('access/templates', 'Personal project');
    }


    /**
     * Получить список названий ролей
     * @return array - [roleName => roleLabel, ...]
     */
    public static function getRolesLabels()
    {
        return [
            static::OWNER => Yii::t('access/templates', 'Project owner'),
        ];
    }


    /**
     * Получить иерархию полномочий
     * @return array - [roleName => [permissionName, ...], ...]
     */
    public static function getRolesHierarchy()
    {
        return [
            static::OWNER    => [
                Accesses::PROJECT_SETTINGS,
                Accesses::MANAGE_COMMENT,
                Accesses::OPEN_TASK,
                Accesses::EDIT_TASK,
                Accesses::CHANGE_STAGE,
                Accesses::CLOSE_TASK,
                Accesses::PROJECT_VIEW,
                Accesses::CREATE_COMMENT
            ],
        ];
    }
}