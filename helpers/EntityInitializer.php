<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 21.10.17
 * Time: 23:30
 */

namespace app\helpers;

use Yii;
use yii\helpers\Json;
use app\components\AccessManager;
use app\models\entities\Project;
use app\models\entities\Task;
use app\models\entities\DictStage;
use app\models\queries\DictStageQuery;


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
        $project->last_task_index = 1;
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
        static::createProjectAccesses($project);

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


    /**
     * Создать и настроить роли и полномочия для проекта.
     *
     * @param $project
     */
    public static function createProjectAccesses($project)
    {
        /** @var AccessManager $auth */
        $auth = Yii::$app->get('authManager');

        $root = $auth->getRole(Access::ROOT);

        $admin = $auth->addRole(
            Access::ADMIN,
            [$root],
            $project
        );
        $employee = $auth->addRole(
            Access::EMPLOYEE,
            [$admin],
            $project
        );
        $view = $auth->addRole(
            Access::VIEW,
            [$employee],
            $project
        );

        $auth->addPermission(
            Access::PROJECT_SETTINGS,
            [$admin],
            $project
        );
        $auth->addPermission(
            Access::OPEN_TASK,
            [$employee],
            $project
        );
        $auth->addPermission(
            Access::EDIT_TASK,
            [$employee],
            $project
        );
        $auth->addPermission(
            Access::CLOSE_TASK,
            [$employee],
            // пока будем считать, что работник может закрывать задачи. (но потом в админке это можно будет выключить)
            $project
        );
    }


    /**
     * Правильно удалить все связанное с проектом
     * @param Project $project
     */
    public static function deinitializeProject($project)
    {
        /** @var AccessManager $auth */
        $auth = Yii::$app->authManager;

        // удалим связанные с проектом полномочия
        $auth->removeProjectAccesses($project);

        // если уж проект удаляют, удалим каскадом все, с ним связанное.
        // правда в случае аттачмента это может потом сказаться... (хотя это тоже можно решить, например сервисными командами уборки)
    }


    /**
     * Правильно удалить все связанное с задачей
     * @param Task $task
     */
    public static function deinitializeTask($task)
    {

    }
}
