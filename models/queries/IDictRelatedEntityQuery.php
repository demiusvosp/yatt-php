<?php
/**
 * User: demius
 * Date: 12.12.17
 * Time: 21:50
 */

namespace app\models\queries;


/**'
 * Interface IDictRelatedEntityQuery - Запросы получения связанных к справочнику сущностей.
 *
 * @package app\models\queries
 */
interface IDictRelatedEntityQuery
{
    /**
     * Количество использующих справочник сущностей
     * @param string $relatedField
     * @return array|null - [dict_id => entity_count]
     */
    public function relatedEntityCount($relatedField = '');

}