<?php

namespace app\models\queries;

use \app\models\entities\Project;
/**
 * This is the ActiveQuery class for [[\app\models\entities\DictState]].
 *
 * @see \app\models\entities\DictState
 */
class DictStateQuery extends \yii\db\ActiveQuery
{
    public function __construct($modelClass, array $config = [])
    {
        parent::__construct($modelClass, $config);

        $this->orderBy(['position' => 'asc']);
    }


    public function andProject($project)
    {
        if($project instanceof Project) {
            return $this->andWhere(['project_id' => $project->id]);
        } else {
            return $this->andWhere(['project_id' => $project]);
        }
    }

    /**
     * @inheritdoc
     * @return \app\models\entities\DictState[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\entities\DictState|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
