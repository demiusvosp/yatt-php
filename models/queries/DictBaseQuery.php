<?php
/**
 * User: demius
 * Date: 19.11.17
 * Time: 20:33
 */

namespace app\models\queries;

use yii\db\ActiveQuery;
use app\models\entities\Project;


/**
 * Class DictBaseQuery - Стандартные запросы к справочникам, единые для любых справочников
 *
 * @package app\models\queries
 */
abstract class DictBaseQuery extends ActiveQuery
{
    /**
     * @param Project|integer $project
     * @return false|null|string
     */
    public function getLastPosition($project)
    {
        return $this
            ->select('position')
            ->andProject($project)
            ->orderBy('position DESC')
            ->limit(1)
            ->scalar();
    }


    /**
     * Относящайся к проекту
     * @param Project|integer $project
     * @return $this
     */
    public function andProject($project)
    {
        if($project instanceof Project) {
            return $this->andWhere(['project_id' => $project->id]);
        } else {
            return $this->andWhere(['project_id' => $project]);
        }
    }
}