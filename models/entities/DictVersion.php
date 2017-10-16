<?php

namespace app\models\entities;

use Yii;
use yii\db\ActiveRecord;

use app\models\queries\DictVersionQuery;

/**
 * This is the model class for table "dict_version".
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property string $description
 * @property integer $position
 *
 * @property Project $project
 * @property Task[] $tasks
 */
class DictVersion extends ActiveRecord
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
        return [
            [['project_id', 'position', 'type'], 'integer'],
            [['name'], 'required'],
            [['name', 'description'], 'string', 'max' => 255],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('dicts', 'ID'),
            'project_id' => Yii::t('dicts', 'Project ID'),
            'name' => Yii::t('dicts', 'Name'),
            'description' => Yii::t('dicts', 'Description'),
            'position' => Yii::t('dicts', 'Position'),
            'type' => Yii::t('dicts', 'Version type'),
        ];
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
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['dict_version_id' => 'id']);
    }

}
