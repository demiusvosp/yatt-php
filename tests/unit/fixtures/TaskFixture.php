<?php
/**
 * User: demius
 * Date: 18.11.17
 * Time: 23:18
 */

namespace tests\unit\fixtures;

use yii\test\ActiveFixture;
use Faker;
use app\models\entities\Task;
use app\models\entities\User;
use app\models\entities\Project;
use app\models\queries\DictStageQuery;


/**
 * Class TaskFixture - тестовые задачи.
 * Подготовить данные можно, например, так: php yii fixture/generate task --count=20
 * Добавляет для проекта PUB, как это передать из консоли хз.
 *
 * @package app\tests\unit\fixtures
 */
class TaskFixture extends ActiveFixture
{
    const PROJECT = 'STD';
    const TASK_COUNT = 20;

    public $modelClass = 'app\models\entities\Task';

    public $depends = [
        //'tests\unit\fixtures\UserFixture',
        //'tests\unit\fixtures\ProjectFixture',
    ];


    public function load()
    {
        echo "generate task for " . self::PROJECT . "\r\n";
        $assigned      = [
            User::findOne(['username' => 'bob']),
            User::findOne(['username' => 'ivan']),
            User::findOne(['username' => 'alice']),
            //User::findOne(['username' => 'petr']), петра в публичном проекте нет.
        ];

        $faker         = Faker\Factory::create('ru_RU');
        $project       = Project::findOne(self::PROJECT);
        $closedStage   = DictStageQuery::closed($project);
        $versionsClose = $project->getVersions()->andForClose()->all();
        $versionsOpen  = $project->getVersions()->andForOpen()->all();

        for($i = 1; $i <= self::TASK_COUNT; $i++) {
            echo 'add task ' . $i . "\r\n";

            $task = new Task();
            $task->caption = $faker->realText(rand(10, 50));
            $task->description = $faker->realText(rand(10, 300));
            $task->suffix = $project->suffix;
            $task->index  = $project->generateNewTaskIndex();

            if (rand(0, 3) > 0) {
                // у 3/4 задач есть исполнитель, прогресс
                $task->assigned_id = $assigned[array_rand($assigned)]->id;
                $task->progress = $faker->numberBetween(0, 10)*10;
                $task->dict_stage_id      = $project->stages[array_rand($project->stages)]->id;
            } else {
                // у новых задач без исполнителей нет прогресса и этап первый
                $task->progress = 0;
                $task->stage = DictStageQuery::open($project);
            }
            $task->priority           = array_rand(Task::priorityLabels());
            $task->dict_type_id       = $project->types[array_rand($project->types)]->id;
            $task->dict_category_id   = $project->categories[array_rand($project->categories)]->id;
            $task->dict_difficulty_id = $project->difficulties[array_rand($project->difficulties)]->id;
            if ($task->dict_stage_id == $closedStage->id) {
                // у закрытой задачи точно есть исполнитель
                $task->assigned_id = $assigned[array_rand($assigned)]->id;
                // если выпало быть закрытым, закрываем правильно
                $task->close($faker->paragraph());
            }
            $task->dict_version_close_id = $versionsClose[array_rand($versionsClose)]->id;
            if (rand(0, 1)) {
                // в половине случаев у нас есть в какой версии появилась задача
                $task->dict_version_open_id = $versionsOpen[array_rand($versionsOpen)]->id;
            }

            if (!$task->save()) {
                echo 'Error in save task ' . $i . "\n";
                var_dump($task->getErrors());
            }

        }
    }


    public function unload()
    {
        echo 'delete tasks from ' . self::PROJECT . "\r\n";
        // удалим все задачи проекта, все равно выцепить эти нет возможности
        Task::deleteAll(['suffix' => self::PROJECT]);
    }
}