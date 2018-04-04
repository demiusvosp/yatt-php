<?php
/**
 * User: demius
 * Date: 28.10.17
 * Time: 0:46
 */

namespace app\components\auth;


use app\models\entities\Project;


/**
 * Class Accesses - Набор предустановленных ролей и полномочий
 *
 * @package app\helpers
 */
class Accesses
{

    /**
     * Глобальные роли и полномочия
     *
     * @return array
     */
    public static function globalItems()
    {
        return [
            Role::ROOT,
            Role::USER,
            Role::GUEST,

            Permission::MANAGEMENT_PROJECT,
            Permission::MANAGEMENT_USER,
            Permission::MANAGEMENT_ACCESS,
        ];
    }


    /**
     * Является ли роль глобальной
     *
     * @param $accessItem
     * @return bool
     */
    public static function isGlobal($accessItem)
    {
        return in_array($accessItem, static::globalItems());
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
        if (strpos('_', $accessItem) !== false) {
            // роль/полномочие уже скомбинированы с проектом
            return $accessItem;
        }

        if (isset(Permission::itemLabels()[$accessItem])) {
            return Permission::getFullName($accessItem, $project);

        } else {
            // все, что не полномочие - считаем ролью
            return Role::getFullName($accessItem, $project);
        }
    }

}
