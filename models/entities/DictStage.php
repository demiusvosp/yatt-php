<?php

namespace app\models\entities;


use app\models\queries\DictStageQuery;

/**
 * This is the model class for table "dict_state".
 *
 * @property integer $type
 */
class DictStage extends DictBase
{

    // типы справочника
    const TYPE_USER = 0; // пользовательский, их можно добавлять в любом количестве
    const TYPE_OPEN = 1; // Задача создана. Всегда первый и дефолтный для задачи
    const TYPE_CLOSED = 2; // Задача закрыта. Всегда последний.


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dict_stage';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['type'], 'integer', 'min' => 0, 'max' => 3],
        ]);
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


    public function beforeSave($insert)
    {
        if ($this->type == static::TYPE_OPEN) {
            $this->position = 0;
        }
        if ($this->type == static::TYPE_CLOSED) {
            $this->position = PHP_INT_MAX;
        }
        return parent::beforeSave($insert);
    }

}
