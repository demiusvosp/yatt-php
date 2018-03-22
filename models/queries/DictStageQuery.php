<?php

namespace app\models\queries;


use app\models\entities\DictStage;


/**
 * This is the ActiveQuery class for [[DictStage]].
 *
 * @see DictStage
 */
class DictStageQuery extends DictBaseQuery
{

    public function __construct($modelClass, array $config = [])
    {
        parent::__construct($modelClass, $config);
        $this->from(['stage' => DictStage::tableName()]);
    }


    /**
     * Получить последнюю позицию справочника.
     * @param \app\models\entities\Project|int $project
     * @return false|null
     */
    public function getLastPosition($project)
    {
        return $this
            ->select('position')
            ->andProject($project)
            ->andOpen()
            ->orderBy('position DESC')
            ->limit(1)
            ->scalar();
    }


    /**
     * Не закрытые
     * @param bool $onlyOpen - строго только этап открыта
     * @return $this
     */
    public function andOpen($onlyOpen = false)
    {
        if($onlyOpen) {
            return $this->andWhere(['type' => DictStage::TYPE_OPEN]);
        } else {
            return $this->andWhere(['<>', 'type', DictStage::TYPE_CLOSED]);
        }
    }


    /**
     * Только закрытые
     * @return $this
     */
    public function andClosed()
    {
        return $this->andWhere(['type' => DictStage::TYPE_CLOSED]);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\DictStage[]|array
     */
    public function all($db = null)
    {
        $this->orderBy(['position' => 'asc']);
        return parent::all($db);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\DictStage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    /**
     * Получить этап Открыт
     * @param $project
     * @return DictStage|array|null
     */
    public static function open($project)
    {
        return DictStage::find()->andProject($project)->andOpen(true)->one();
    }


    /**
     * Получиь этап Закрыт
     * @param $project
     * @return DictStage|array|null
     */
    public static function closed($project)
    {
        return DictStage::find()->andProject($project)->andClosed()->one();
    }

}
