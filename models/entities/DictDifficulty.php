<?php

namespace app\models\entities;

use Yii;

/**
 * This is the model class for table "dict_difficulty".
 *
 * @property double  $ratio
 *
 * @property Task[]  $tasks
 */
class DictDifficulty extends DictBase
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dict_difficulty';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['ratio'], 'number'],
        ]);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'ratio' => Yii::t('dicts', 'Ratio'),
        ]);
    }


    /**
     * @inheritdoc
     * @return \app\models\queries\DictDifficultyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\DictDifficultyQuery(get_called_class());
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['dict_difficulty_id' => 'id']);
    }

}
