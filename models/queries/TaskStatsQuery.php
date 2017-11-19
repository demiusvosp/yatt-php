<?php
/**
 * User: demius
 * Date: 19.11.17
 * Time: 1:22
 */

namespace app\models\queries;

use app\models\entities\Project;


/**
 * Class TaskStatsQuery - разная статистика по задачам проекта.
 * первый кандидат на кеширование
 *
 * @package app\models\queries
 */
class TaskStatsQuery extends TaskQuery
{
    /**
     * Сколько задач у проекта
     * @param Project $project
     * @return int|string
     */
    public function statAllTasks($project)
    {
        return $this->andProject($project)->count();
    }


    /**
     * Сколько еще открытых задач
     * @param $project
     * @return int|string
     */
    public function statOpenTasks($project)
    {
        return $this->andProject($project)->andClosed(false)->count();
    }


    /**
     * Прогресс задач
     * @param $project
     */
    public function statTasksProgress($project)
    {

    }
}