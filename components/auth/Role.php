<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 18:25
 */

namespace app\components\auth;


/**
 * Class Role - обертка над yii\rbac\Role, позволяющая сохранить в ней дополнительную необходимую инфу.
 * Не модель!
 *
 * @package app\components\access
 */
class Role extends \yii\rbac\Role implements IItem
{
    const TYPE_GLOBAL = 0;
    const TYPE_PROJECT = 1;

    use TItem;
}
