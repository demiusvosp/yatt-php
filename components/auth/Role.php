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
     * Создать элемент доступа
     *
     * @param string       $name
     * @param Project|null $project
     * @param              $label - описание
     * @return Role|Permission
     */
    public static function create($name, $project = null, $label = '')
    {
        $item = new static();

        // если доступ встроенный берем описание через хелпер
        if (isset(static::itemLabels()[$name])) {
            $label               = static::itemLabels()[$name];
            $item->name          = static::getFullName($name, $project);
            $item->data['embed'] = true;
        } else {
            $item->data['embed'] = false;
        }

        if ($project) {
            $item->name            = $project->suffix . '_' . $name;
            $item->data['project'] = $project->suffix;
            $item->description     = $project->name . ': ' . $label;
        } else {
            $item->name            = $name;
            $item->data['project'] = null;
            $item->description     = $label;
        }

        return $item;
    }


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
            static::GUEST              => Yii::t('access', 'guest'),
        ];
    }

    /**
     * Получить полное имя роли
     *
     * @param $id
     * @param Project $project
     * @return string
     */
    public static function getFullName($id, $project)
    {
        if(Accesses::isGlobal($id)) {
            // это глобальная роль
            return $id;
        }

        if(count(explode('_', $id)) > 1) {
            // уже полное имя
            return $id;
        }

        if($project) {
            return $project->suffix . '_' . $id;
        }
        return $id;
    }


    /**
     * Проверить является ли роль относящейся к проекту
     *
     * @param $id
     * @return bool
     */
    public static function isProjectItem($id)
    {
        if(Accesses::isGlobal($id)) {
            // это глобальная роль
            return false;
        }

        if(count(explode('_', $id)) > 1) {
            // Да в имени есть проект
            return true;
        }

        return false;
    }
}
