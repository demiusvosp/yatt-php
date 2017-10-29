<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 19:23
 */

namespace app\components\access;


/**
 * Trait ItemExtendInfo - функционал вытаскивания дополнительных данных из ролей/полномочий yii\rbac
 *
 * @package Yatt\access
 */
trait ItemExtendInfo
{

    /**
     * @param bool   $isProject
     * @param string $label
     * @return string
     */
    public static function setData($isProject, $label)
    {
        return serialize([
            'is_project' => $isProject ? true : false,// пока мы не отказываемся от поддержки PHP5, так безопаснее
            'label'   => $label,
        ]);
    }


    /**
     * @return array [is_project, label]
     */
    public function getData()
    {
        return unserialize($this->data);
    }


    /**
     * @return string
     */
    public function getLabel()
    {
        $data = $this->getData();

        return $data['label'];
    }


    /**
     * @return bool
     */
    public function isProject()
    {
        $data = $this->getData();

        return (bool) $data['is_project'];
    }


    /**
     * @return bool
     */
    public function isGlobal()
    {
        $data = $this->getData();

        return ! $data['is_project'];
    }

}
