<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 11.10.17
 * Time: 21:47
 */

namespace app\models\entities;


/**
 * Interface IWithProject - модель принадлежит проекту, т.е. имеет поле проект
 * @package app\models\entities
 */
interface IWithProject
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject();
}