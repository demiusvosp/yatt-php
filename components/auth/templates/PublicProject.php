<?php
/**
 * User: demius
 * Date: 28.03.18
 * Time: 21:58
 */

namespace app\components\auth\templates;


use Yii;
use app\components\auth\Accesses;


/**
 * Class PublicProject - Публичный проект, проект, который виен всем, а не только уполномоченным
 *
 * @package app\components\auth\templates
 */
class PublicProject implements IAccessesTemplate
{
    const ADMIN = 'projectAdmin';
    const EMPLOYEE = 'projectEmployee';


    /**
     * Получить имя шаблона полномочий
     * @return string
     */
    public static function name()
    {
        return Yii::t('access/templates', 'Public project');
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
                '?',
                Accesses::OPEN_TASK,
                Accesses::EDIT_TASK,
                Accesses::CHANGE_STAGE,
                Accesses::CLOSE_TASK
            ],
            '?' => [
                Accesses::PROJECT_VIEW,
                Accesses::CREATE_COMMENT
            ],
        ];
    }
}