<?php

namespace app\models\entities;


use app\models\queries\DictStageQuery;

/**
 * This is the model class for table "dict_state".
 *
 */
class DictStage extends DictBase
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dict_stage';
    }


    /**
     * @inheritdoc
     * @return DictStageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DictStageQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['dict_stage_id' => 'id']);
    }

}
