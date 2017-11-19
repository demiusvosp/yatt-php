<?php
/**
 * User: demius
 * Date: 19.11.17
 * Time: 1:22
 */

namespace app\models\queries;

use app\models\entities\Project;
use app\models\entities\Task;


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
    public static function statAllTasks($project)
    {
        $query = (new TaskQuery(Task::className()))->andProject($project);
        return $query->count('id');
    }


    /**
     * Сколько еще открытых задач
     * @param $project
     * @return int|string
     */
    public static function statOpenTasks($project)
    {
        $query = (new TaskQuery(Task::className()))->andProject($project);
        return $query->andClosed(false)->count('id');
    }


    /**
     * Прогресс задач
     * @param $project
     */
    public static function statTasksProgress($project)
    {
        $query = (new TaskQuery(Task::className()))->andProject($project);
        //$query->select('AVG(progress)');
        return floatval($query->average('progress'));
    }
}