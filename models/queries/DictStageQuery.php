<?php

namespace app\models\queries;

use yii\db\ActiveQuery;
use app\models\entities\DictStage;
use app\models\entities\Project;


/**
 * This is the ActiveQuery class for [[DictStage]].
 *
 * @see DictStage
 */
class DictStageQuery extends ActiveQuery
{

    public function __construct($modelClass, array $config = [])
    {
        parent::__construct($modelClass, $config);
        $this->from(['stage' => DictStage::tableName()]);
    }


    public function andProject($project)
    {
        if($project instanceof Project) {
            return $this->andWhere(['project_id' => $project->id]);
        } else {
            return $this->andWhere(['project_id' => $project]);
        }
    }


    public function andOpen()
    {
        return $this->andWhere(['type' => DictStage::TYPE_OPEN]);
    }


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
        return DictStage::find()->andProject($project)->andOpen()->one();
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
