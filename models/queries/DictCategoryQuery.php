<?php

namespace app\models\queries;

use app\models\entities\DictCategory;


/**
 * This is the ActiveQuery class for [[\app\models\entities\DictCategory]].
 *
 * @see \app\models\entities\DictCategory
 */
class DictCategoryQuery extends DictBaseQuery
{

    public function __construct($modelClass, array $config = [])
    {
        parent::__construct($modelClass, $config);
        $this->from(['category' => DictCategory::tableName()]);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\DictCategory[]|array
     */
    public function all($db = null)
    {
        $this->orderBy(['position' => 'asc']);

        return parent::all($db);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\DictCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}
