<?php
/**
 * User: demius
 * Date: 18.11.17
 * Time: 17:54
 */

namespace tests\unit\fixtures;


use yii\test\ActiveFixture;
use app\models\entities\Project;
use app\models\entities\User;


class ProjectFixture extends ActiveFixture
{
    public $modelClass = 'app\models\entities\Project';

    public $depends = [
        //'tests\unit\fixtures\UserFixture', иначе он удалит всех пользователей
    ];


    public function load()
    {
        $bob  = User::findOne(['username' => 'bob']);
        $ivan = User::findOne(['username' => 'ivan']);

        foreach ($this->getData() as $alias => $row) {
            $project = new Project();
            $project->setAttributes($row);
            if ($alias == 'priv') {
                $project->admin_id = $bob->id;
            }
            if ($alias == 'oth') {
                $project->admin_id = $ivan->id;
            }

            if (!$project->save()) {
                var_dump($project->getErrors());
                echo 'Error in save project ' . $row['suffix'];
            }
            $this->data[$alias] = array_merge($row, ['id' => $project->id]);
        }

    }


    public function unload()
    {
        foreach ($this->getData() as $alias => $row) {
            $project = Project::findOne($row['suffix']);
            if ($project) {
                echo 'delete project ' . $project->suffix;
                $project->delete();
            }
        }
    }
}