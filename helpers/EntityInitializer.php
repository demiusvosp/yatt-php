<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 21.10.17
 * Time: 23:30
 */

namespace app\helpers;

use Yii;
use app\models\entities\Project;
use app\models\entities\Task;
use app\models\entities\DictStage;
use app\models\queries\DictStageQuery;
use yii\helpers\Json;

class EntityInitializer
{
    /**
     * Действия, необходимые при созданиии проекта
     * Лежит отдельно, а не на событии, так как не всегда нам нужно инициализировать проект
     * @param Project $project
     */
    public static function initializeProject($project, $andSave = true)
    {
        // дефолтные настройки
        $project->last_task_id = 1;
        $project->config = Json::encode([]);

        // создаем минимально необходимые этапы
        $open = new DictStage(
            [
                'name' => Yii::t('dicts', 'Open'),
                'description' => Yii::t('dicts', 'Open task'),
                'type' => DictStage::TYPE_OPEN
            ]
        );
        $open->link('project', $project);

        $close = new DictStage(
            [
                'name' => Yii::t('dicts', 'Close'),
                'description' => Yii::t('dicts', 'Close task'),
                'type' => DictStage::TYPE_CLOSED
            ]
        );
        $close->link('project', $project);

        // Создадим для проекта необходимые роли и полномочия
        Yii::$app->get('accessService')->createProjectAccesses($project);

        if($andSave) {
            $project->save();
        }
    }

    /**
     * @param Task $task
     * @param Project $project
     */
    public static function initializeTask($task, $project)
    {
        $task->suffix = $project->suffix;
        $task->dict_stage_id = DictStageQuery::open($project)->id;
        $task->priority = Task::PRIORITY_MEDIUM;
        $task->assigned_id = Yii::$app->user->identity->getId();
        $task->is_closed = false;
    }
}
