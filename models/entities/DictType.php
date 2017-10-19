<?php

namespace app\models\entities;


use app\models\queries\DictTypeQuery;

/**
 * This is the model class for table "dict_type".
 *
 * @property Task[]  $tasks
 */
class DictType extends DictBase
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dict_type';
    }


    /**
     * @inheritdoc
     * @return DictTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DictTypeQuery(get_called_class());
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['dict_type_id' => 'id']);
    }
}
