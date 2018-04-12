<?php
/**
 * User: demius
 * Date: 18.11.17
 * Time: 17:54
 */

namespace tests\unit\fixtures;

use Yii;
use yii\test\ActiveFixture;
use app\components\auth\AccessBuilder;
use app\components\auth\AuthProjectManager;
use app\models\entities\Project;
use app\models\entities\User;
use app\models\entities\DictCategory;
use app\models\entities\DictDifficulty;
use app\models\entities\DictStage;
use app\models\entities\DictType;
use app\models\entities\DictVersion;


class ProjectFixture extends ActiveFixture
{
    public $modelClass = 'app\models\entities\Project';

    public $depends = [
        //'tests\unit\fixtures\UserFixture', иначе он удалит всех пользователей
    ];


    public function load()
    {
        echo "generate test projects \r\n";

        $alice = User::findOne(['username' => 'alice']);
        $bob  = User::findOne(['username' => 'bob']);
        $ivan = User::findOne(['username' => 'ivan']);

        // выдадим доступы
        /** @var AccessBuilder $accessBuilder */
        $accessBuilder = Yii::$app->get('accessBuilder');

        foreach ($this->getData() as $alias => $row) {
            echo "project " . $alias . "\r\n";
            $project = new Project();
            $project->setAttributes($row);
            $project->setConfigItem('editorType', $row['editorType']);

            if (!$project->save()) {
                var_dump($project->getErrors());
                echo 'Error in save project ' . $row['suffix'];
            }
            $this->data[$alias] = array_merge($row, ['id' => $project->id]);

            echo "\n build accesses roles";
            $accessBuilder->buildProjectAccesses($project, $row['access_template']);

            echo "\n build dictionaries";
            // разные справочники
            (new DictStage(['name' => 'Разработка']))->append($project);
            (new DictStage(['name' => 'Тестирование']))->append($project);

            (new DictVersion(['name' => '1.0', 'type' => DictVersion::CURRENT]))->append($project);
            (new DictVersion(['name' => '1.1', 'type' => DictVersion::FUTURE]))->append($project);
            (new DictVersion(['name' => '2.0', 'type' => DictVersion::FUTURE]))->append($project);

            (new DictType(['name' => 'Задача']))->append($project);
            (new DictType(['name' => 'Ошибка']))->append($project);
            (new DictType(['name' => 'Рефакторинг']))->append($project);

            (new DictDifficulty(['name' => 'Простая', 'ratio' => 0.5]))->append($project);
            (new DictDifficulty(['name' => 'Обычная', 'ratio' => 1]))->append($project);
            (new DictDifficulty(['name' => 'Сложная', 'ratio' => 2]))->append($project);

            (new DictCategory(['name' => 'Ядро']))->append($project);
            (new DictCategory(['name' => 'API']))->append($project);
            (new DictCategory(['name' => 'Админка']))->append($project);
            (new DictCategory(['name' => 'Консоль']))->append($project);

        }

    }


    public function unload()
    {
        /** @var AuthProjectManager $auth */
        $auth = Yii::$app->get('authManager');
        echo "delete generated projects \r\n";
        foreach ($this->getData() as $alias => $row) {
            $project = Project::findOne($row['suffix']);
            if ($project) {
                $auth->removeProjectAccesses($project);
                echo 'delete project ' . $project->suffix;
                $project->delete();
            }
        }
    }
}