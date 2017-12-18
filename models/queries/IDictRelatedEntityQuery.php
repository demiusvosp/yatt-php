<?php
/**
 * User: demius
 * Date: 12.12.17
 * Time: 21:50
 */

namespace app\models\queries;

use app\models\entities\Project;


/**'
 * Interface IDictRelatedEntityQuery - Запросы получения связанных к справочнику сущностей.
 *
 * @package app\models\queries
 */
interface IDictRelatedEntityQuery
{
    /**
     * Количество использующих справочник сущностей
     * @param Project $project
     * @param string $relatedField
     * @return array|null - [dict_id => entity_count]
     */
    public function relatedEntityCount($project, $relatedField = '');

}
