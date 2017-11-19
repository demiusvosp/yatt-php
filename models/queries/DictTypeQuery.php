<?php

namespace app\models\queries;


use app\models\entities\DictType;


/**
 * This is the ActiveQuery class for [[DictType]].
 *
 * @see DictType
 */
class DictTypeQuery extends DictBaseQuery
{
    public function __construct($modelClass, array $config = [])
    {
        parent::__construct($modelClass, $config);
        $this->from(['type' => DictType::tableName()]);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\DictType[]|array
     */
    public function all($db = null)
    {
        $this->orderBy(['position' => 'asc']);
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
