<?php

namespace app\models\queries;

use yii\db\ActiveQuery;
use app\models\entities\DictVersion;

/**
 * This is the ActiveQuery class for [[DictVersion]].
 *
 * @see DictVersion
 */
class DictVersionQuery extends ActiveQuery
{

    public function __construct($modelClass, array $config = [])
    {
        parent::__construct($modelClass, $config);
        $this->from(['version' => DictVersion::tableName()]);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\DictVersion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\DictVersion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}
