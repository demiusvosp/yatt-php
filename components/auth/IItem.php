<?php
/**
 * User: demius
 * Date: 24.03.18
 * Time: 1:29
 */

namespace app\components\auth;


interface IItem
{
    /**
     * @param string $label
     */
    public function setLabel($label);


    /**
     * @return string
     */
    public function getLabel();


    /**
     * @param bool $is_project
     */
    public function setIsProject($is_project);


    /**
     * @return bool
     */
    public function isProject();


    /**
     * @return bool
     */
    public function isGlobal();


    /**
     * Получить суффикс проекта, ассоциированного с элементом доступа
     * @return null|string
     */
    public function getProject();
}