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
     * Версии в которых задача может быть обнаружена (прошлые и текущие)
     * @return $this
     */
    public function andForOpen()
    {
        return $this->andWhere(['or', ['version.type' => DictVersion::PAST], ['version.type' => DictVersion::CURRENT]]);
    }


    /**
     * Версии в которых задача может быть закрыта (текущие и будущие)
     * @return $this
     */
    public function andForClose()
    {
        return $this->andWhere(['or', ['version.type' => DictVersion::CURRENT], ['version.type' => DictVersion::FUTURE]]);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\DictVersion[]|array
     */
    public function all($db = null)
    {
        $this->orderBy(['position' => 'asc', 'type' => 'desc']);
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
