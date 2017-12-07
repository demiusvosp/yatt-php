<?php

namespace app\models\entities;



/**
 * This is the model class for table "dict_category".
 *
 * @property Task[]  $tasks
 */
class DictCategory extends DictBase
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dict_category';
    }


    /**
     * @inheritdoc
     * @return \app\models\queries\DictCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\DictCategoryQuery(get_called_class());
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['dict_category_id' => 'id']);
    }


    /**
     * Количество задач использующих значение справочника
     * @return int
     */
    public function countTask()
    {
        return $this->getTasks()->count('id');
    }

}
