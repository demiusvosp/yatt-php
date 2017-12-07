<?php

namespace app\models\entities;

use Yii;

use app\models\queries\DictVersionQuery;

/**
 * This is the model class for table "dict_version".
 *
 * @property integer $type
 *
 * @property $tasksOnOpen
 * @property $tasksOnClose
 */
class DictVersion extends DictBase
{
    // типы версий
    const FUTURE  = 0;// Планируемая (только ожидается к)
    const CURRENT = 1;// Текущая (и ожидается к, и обнаружена в)
    const PAST    = 2;// Прошедшая (только обнаружена в)


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dict_version';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['type'], 'integer', 'min' => 0, 'max' => count(static::typesLabels())],
        ]);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'type' => Yii::t('dicts', 'Type'),
        ]);
    }


    public static function typesLabels()
    {
        return [
            static::FUTURE  => Yii::t('dicts', 'Future'),
            static::CURRENT => Yii::t('dicts', 'Current'),
            static::PAST    => Yii::t('dicts', 'Past'),
        ];
    }


    /**
     * @inheritdoc
     * @return DictVersionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DictVersionQuery(get_called_class());
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasksOnOpen()
    {
        return $this->hasMany(Task::className(), ['dict_version_open_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasksOnClose()
    {
        return $this->hasMany(Task::className(), ['dict_version_close_id' => 'id']);
    }


    /**
     * Количество задач использующих значение справочника
     * @return int
     */
    public function countTask()
    {
        return $this->getTasksOnOpen()->count('id') + $this->getTasksOnClose()->count('id');
    }

}
