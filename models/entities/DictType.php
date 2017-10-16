<?php

namespace app\models\entities;

use Yii;
use yii\db\ActiveRecord;

use app\models\queries\DictTypeQuery;

/**
 * This is the model class for table "dict_type".
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
class DictType extends ActiveRecord
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
     */
    public function rules()
    {
        return [
            [['project_id', 'position'], 'integer'],
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
        ];
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
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['dict_type_id' => 'id']);
    }
}
