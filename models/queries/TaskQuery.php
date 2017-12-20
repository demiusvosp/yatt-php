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
class TaskQuery extends ActiveQuery implements IDictRelatedEntityQuery
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
     * @return $this
     */
    public function andClosed()
    {
        return $this->andWhere(['is_closed' => true]);
    }


    /**
     * Только не закрытые
     * @return $this
     */
    public function andOpen()
    {
        return $this->andWhere(['<>', 'is_closed', true]);
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


    public function relatedEntityCount($project, $relatedField = '')
    {
        $query = Task::find()
            ->andProject($project)
            ->groupBy([$relatedField])
            ->select([$relatedField . ' as dict_id', 'count(task.id) as n'])
            ->andWhere(['not', [$relatedField => null]])
            ->asArray();

        $result = [];
        foreach ($query->all() as $item) {
            $result[$item['dict_id']] = $item['n'];
        }
        return $result;
    }


    /**
     * Посчитать незакрытые задачи по версиям в которых они должны быть закрыты
     * @param Project $project
     * @return array
     */
    public static function versionWithOpenedTaskCount($project)
    {
        $query = Task::find()
            ->andProject($project)
            ->groupBy(['dict_version_close_id'])
            ->select(['dict_version_close_id as dict_id', 'count(task.id) as n'])
            ->andWhere(['not', ['dict_version_close_id' => null]])
            ->asArray();

        $result = [];
        foreach ($query->all() as $item) {
            $result[$item['dict_id']] = $item['n'];
        }
        return $result;
    }


    public static function getByIndex($suffix, $index)
    {
        return Task::find()->andWhere(['suffix' => $suffix, 'index' => $index])->one();
    }

}
