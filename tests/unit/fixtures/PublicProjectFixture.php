<?php
/**
 * User: demius
 * Date: 18.11.17
 * Time: 22:28
 */

namespace tests\unit\fixtures;


use Yii;
use yii\test\ActiveFixture;
use app\components\AccessManager;
use app\helpers\Access;
use app\models\entities\Project;
use app\models\entities\User;
use app\models\entities\DictCategory;
use app\models\entities\DictDifficulty;
use app\models\entities\DictStage;
use app\models\entities\DictType;
use app\models\entities\DictVersion;


class PublicProjectFixture extends ActiveFixture
{
    const PROJECT = 'PUB';

    public $modelClass = 'app\models\entities\Project';

    public $depends = [
        'tests\unit\fixtures\UserFixture',
    ];


    public function load()
    {
        echo 'create Project PUB';
        /** @var AccessManager $auth */
        $auth = Yii::$app->authManager;

        $bob   = User::findOne(['username' => 'bob']);
        $ivan  = User::findOne(['username' => 'ivan']);
        $alice = User::findOne(['username' => 'alice']);

        $project = new Project();
        $project->setAttributes([
            'suffix'      => self::PROJECT,
            'name'        => 'Публичный проект',
            'description' => 'Видный всем проект, где больше всего всяких фич. Для разработки и отладки',
            'public'      => Project::STATUS_PUBLIC_ALL,
            'admin_id'    => $bob->id,
        ]);

        if (!$project->save()) {
            var_dump($project->getErrors());
            throw new \DomainException('Cannot add project');
        }

        // работники проекта
        $auth->assign(Access::projectItem(Access::EMPLOYEE), $ivan->id, $project);
        $auth->assign(Access::projectItem(Access::EMPLOYEE), $alice->id, $project);

        // разные справочники
        (new DictStage(['name' => 'Разработка']))->link('project', $project);
        (new DictStage(['name' => 'Тестирование']))->link('project', $project);

        (new DictVersion(['name' => '1.0', 'type' => DictVersion::CURRENT]))->link('project', $project);
        (new DictVersion(['name' => '1.1', 'type' => DictVersion::FUTURE]))->link('project', $project);
        (new DictVersion(['name' => '2.0', 'type' => DictVersion::FUTURE]))->link('project', $project);

        (new DictType(['name' => 'Задача']))->link('project', $project);
        (new DictType(['name' => 'Ошибка']))->link('project', $project);
        (new DictType(['name' => 'Рефакторинг']))->link('project', $project);

        (new DictDifficulty(['name' => 'Простая', 'ratio' => 0.5]))->link('project', $project);
        (new DictDifficulty(['name' => 'Обычная', 'ratio' => 1]))->link('project', $project);
        (new DictDifficulty(['name' => 'Сложная', 'ratio' => 2]))->link('project', $project);

        (new DictCategory(['name' => 'Ядро']))->link('project', $project);
        (new DictCategory(['name' => 'API']))->link('project', $project);
        (new DictCategory(['name' => 'Админка']))->link('project', $project);
        (new DictCategory(['name' => 'Консоль']))->link('project', $project);
    }


    public function unload()
    {
        $project = Project::findOne(self::PROJECT);
        if ($project) {
            echo 'delete project ' . $project->suffix;
            $project->delete();
        }
    }
}