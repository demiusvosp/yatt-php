<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 19:23
 */

namespace app\components\auth;


use app\models\entities\Project;


/**
 * Trait TItem - функционал вытаскивания дополнительных данных из ролей/полномочий yii\rbac
 *
 * @property string  $label
 * @property string  $project
 * @property boolean $embed - является роль встроенной или созданной пользователем
 */
trait TAccessItem
{

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
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }


    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->description;
    }


    /**
     * @return bool
     */
    public function isProject()
    {
        return (bool)!empty($this->data['project']);
    }


    /**
     * @return bool
     */
    public function isGlobal()
    {
        return (bool)empty($this->data['project']);
    }


    /**
     * @return bool
     */
    public function isEmbed()
    {
        return (bool)$this->data['embed'];
    }


    /**
     * Получить суффикс проекта, ассоциированного с элементом доступа
     *
     * @return null|string
     */
    public function getProject()
    {
        if ($this->isGlobal()) {
            return null;
        }

        return $this->data['project'];
    }


    /**
     * Получить идентификатор элемента доступа
     * @return mixed
     */
    public function getId()
    {
        if ($this->isGlobal()) {
            return $this->name;
        }

        list( , $id) = explode('_', $this->name);
        return $id;
    }


    /**
     * Получить полное имя полномочия
     * @param $id
     * @param Project $project
     * @return string
     */
    public static function getFullName($id, $project)
    {
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
     * Проверить является ои элемент доступа относящимся к проекту
     * @param $id
     * @return bool
     */
    public static function isProjectItem($id)
    {
        if(count(explode('_', $id)) > 1) {
            // Да в имени есть проект
            return true;
        }

        return false;
    }
}
