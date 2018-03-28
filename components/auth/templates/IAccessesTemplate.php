<?php
/**
 * User: demius
 * Date: 23.03.18
 * Time: 22:53
 */

namespace app\components\auth\templates;


interface IAccessesTemplate
{
    /**
     * Получить имя шаблона полномочий
     * @return string
     */
    public static function name();

    /**
     * Получить список названий ролей
     * @return array - [roleName => roleLabel, ...]
     */
    public static function getRolesLabels();


    /**
     * Получить иерархию полномочий
     * @return array - [roleName => [permissionName, ...], ...]
     */
    public static function getRolesHierarchy();

}