<?php
/**
 * User: demius
 * Date: 24.03.18
 * Time: 1:29
 */

namespace app\components\auth;


interface IAccessItem
{

    /**
     * @return string
     */
    public function getLabel();


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