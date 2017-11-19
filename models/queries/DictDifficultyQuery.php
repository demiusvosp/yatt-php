<?php

namespace app\models\queries;

use app\models\entities\DictDifficulty;


/**
 * This is the ActiveQuery class for [[DictDifficulty]].
 *
 * @see DictDifficulty
 */
class DictDifficultyQuery extends DictBaseQuery
{

    public function __construct($modelClass, array $config = [])
    {
        parent::__construct($modelClass, $config);
        $this->from(['difficulty' => DictDifficulty::tableName()]);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\DictDifficulty[]|array
     */
    public function all($db = null)
    {
        $this->orderBy(['position' => 'asc']);

        return parent::all($db);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\DictDifficulty|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}
