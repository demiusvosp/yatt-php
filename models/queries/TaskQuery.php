<?php

namespace app\models\queries;

use yii\db\ActiveQuery;
use app\models\entities\Task;
use app\models\entities\Project;


/**
 * This is the ActiveQuery class for [[\app\models\entities\Task]].
 *
 * @see \app\models\entities\Task
 */
class TaskQuery extends ActiveQuery
{

    public function __construct($modelClass, array $config = [])
    {
        parent::__construct($modelClass, $config);
        $this->from(['task' => Task::tableName()]);
    }

    /**
     * @param Project|string $project - или текстовый суффикс
     * @return $this
     */
    public function andProject($project)
    {
        if ($project instanceof Project) {
            return $this->andWhere(['task.suffix' => $project->suffix]);
        } else {
            return $this->andWhere(['task.suffix' => $project]);
        }
    }


    /**
     * Только закрытые
     * @param bool $closed
     * @return $this
     */
    public function andClosed($closed = true)
    {
        return $this->andWhere(['is_closed' => $closed]);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\Task[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\entities\Task|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    public static function getByIndex($suffix, $index)
    {
        return Task::find()->andWhere(['suffix' => $suffix, 'index' => $index])->one();
    }
}
