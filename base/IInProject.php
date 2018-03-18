<?php
/**
 * User: demius
 * Date: 17.03.18
 * Time: 18:03
 */

namespace app\base;

/**
 * Interface IInProject - поддерживается объектами, ассоциированными с проектом
 *
 * @package app\models\entities
 */
interface IInProject
{
    public function getProject();
}