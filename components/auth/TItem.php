<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 19:23
 */

namespace app\components\auth;


/**
 * Trait TItem - функционал вытаскивания дополнительных данных из ролей/полномочий yii\rbac
 *
 * @property string $label
 * @property bool $is_project
 */
trait TItem
{
    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->data['label'] = $label;
    }


    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->data['label'];
    }


    /**
     * @param bool $is_project
     */
    public function setIsProject($is_project)
    {
        $this->data['is_project'] = (bool) $is_project;
    }


    /**
     * @return bool
     */
    public function isProject()
    {
        return (bool) $this->data['is_project'];
    }


    /**
     * @return bool
     */
    public function isGlobal()
    {
        return ! $this->data['is_project'];
    }


    /**
     * Получить суффикс проекта, ассоциированного с элементом доступа
     * @return null|string
     */
    public function getProject()
    {
        if($this->isGlobal()) {
            return null;
        }
        list( , $project) = explode('_', $this->name);
        return $project;
    }
}
