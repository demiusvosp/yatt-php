<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 19:19
 */

namespace app\components\access;


/**
 * Class Permission - обертка над yii\rbac\Permission, позволяющая сохранить в ней дополнительную необходимую инфу.
 * Не модель!
 *
 * @package Yatt\access
 */
class Permission extends \yii\rbac\Permission
{
    const TYPE_GLOBAL = 0;
    const TYPE_PROJECT = 1;

    use ItemExtendInfo;
}
