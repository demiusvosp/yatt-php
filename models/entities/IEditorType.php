<?php
/**
 * User: demius
 * Date: 04.01.18
 * Time: 13:15
 */

namespace app\models\entities;


interface IEditorType
{
    /**
     * Получить тип редактора поля
     * @param string $field
     * @return string
     */
    public function getEditorType($field);
}