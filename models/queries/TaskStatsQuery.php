<?php
/**
 * User: demius
 * Date: 19.11.17
 * Time: 1:22
 */

namespace app\models\queries;

use yii\caching\TagDependency;
use app\models\entities\Project;
use app\models\entities\Task;
use app\helpers\CacheTagHelper;


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
        $query = (new static(Task::className()))->andProject($project);
        $query->addCacheTag($project);

        return $query->count('id');
    }


    /**
     * Сколько еще открытых задач
     * @param Project $project
     * @return int|string
     */
    public static function statOpenTasks($project)
    {
        $query = (new static(Task::className()))->andProject($project);
        $query->addCacheTag($project);

        return $query->andOpen()->count('id');
    }


    /**
     * Прогресс задач
     * @param Project $project
     * @return float
     */
    public static function statTasksProgress($project)
    {
        $query = (new static(Task::className()))->andProject($project);
        $query->addCacheTag($project);

        return floatval($query->average('progress * difficulty_ratio'));
    }


    /**
     * Получить прогресс задач по версии.
     * Принимаем версию как $project, $versionId чтобы не породить лишних запросов в БД.
     * @param Project $project
     * @param integer $versionId
     * @return float
     */
    public static function statVersionProgress($project, $versionId)
    {
        $query = (new static(Task::className()))->andProject($project);
        $query->where(['dict_version_close_id' => $versionId]);
        $query->addCacheTag($project);

        return floatval($query->average('progress * difficulty_ratio'));
    }


    /**
     * @param Project $project
     */
    protected function addCacheTag($project)
    {
        $this->cache(0, new TagDependency(['tags' => CacheTagHelper::taskStat($project->suffix)]));
    }

}
