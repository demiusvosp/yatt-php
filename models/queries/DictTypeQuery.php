<?php

namespace app\models\queries;

/**
 * This is the ActiveQuery class for [[\app\models\entities\DictType]].
 *
 * @see \app\models\entities\DictType
 */
class DictTypeQuery extends \yii\db\ActiveQuery
{
    public function __construct($modelClass, array $config = [])
    {
        parent::__construct($modelClass, $config);

        $this->orderBy(['position' => 'asc']);
    }

    /**
     * @inheritdoc
     * @return \app\models\entities\DictType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\entities\DictType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
