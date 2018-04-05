<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 18:25
 */

namespace app\components\auth;


use Yii;
use app\models\entities\Project;


/**
 * Class Role - обертка над yii\rbac\Role, позволяющая сохранить в ней дополнительную необходимую инфу.
 * Не модель!
 *
 * @package app\components\access
 */
class Role extends \yii\rbac\Role implements IAccessItem
{
    const TYPE_GLOBAL = 0;
    const TYPE_PROJECT = 1;

    const BUILT_IN = 2;
    const CUSTOM = 3;

    // глобальные роли
    const ROOT = 'root';
    const USER = 'user';
    const GUEST = 'guest';


    use TAccessItem;


    /**
     * Переводы ролей и полномочий
     *
     * @return array
     */
    public static function itemLabels()
    {
        return [
            static::ROOT  => Yii::t('access', 'root'),
            static::USER  => Yii::t('access', 'user'),
            static::GUEST => Yii::t('access', 'guest'),
        ];
    }


    /**
     * Получить полное имя роли
     *
     * @param         $name
     * @param Project $project
     * @return string
     */
    public static function getFullName($name, $project)
    {
        if (Accesses::isGlobal($name)) {
            // это глобальная роль
            return $name;
        }

        if (strpos($name, '_') !== false) {
            // уже полное имя
            return $name;
        }

        if ($project) {
            return $project->suffix . '_' . $name;
        }

        return $name;
    }


    /**
     * Проверить является ли роль относящейся к проекту
     *
     * @param $name
     * @return bool
     */
    public static function isProjectItem($name)
    {
        if (Accesses::isGlobal($name)) {
            // это глобальная роль
            return false;
        }

        if (strpos($name, '_') !== false) {
            // Да в имени есть проект
            return true;
        }

        return false;
    }
}
