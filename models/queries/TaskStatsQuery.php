<?php
/**
 * User: demius
 * Date: 19.11.17
 * Time: 1:22
 */

namespace app\models\queries;

use app\models\entities\DictVersion;
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
     * @param Project $project
     * @return int|string
     */
    public static function statOpenTasks($project)
    {
        $query = (new TaskQuery(Task::className()))->andProject($project);
        return $query->andOpen()->count('id');
    }


    /**
     * Прогресс задач
     * @param Project $project
     * @return float
     */
    public static function statTasksProgress($project)
    {
        $query = (new TaskQuery(Task::className()))->andProject($project);
        return floatval($query->average('progress * difficulty_ratio'));
    }


    /**
     * @param DictVersion $version
     * @return float
     */
    public static function statVersionProgress($version)
    {
        $query = (new TaskQuery(Task::className()))->andProject($version->project);
        $query->where(['dict_version_close_id' => $version->id]);
        return floatval($query->average('progress * difficulty_ratio'));
    }
}