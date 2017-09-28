<?php

namespace app\models\entities;

use Yii;

/**
 * This is the model class for table "dict_state".
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property string $description
 *
 * @property Project $project
 * @property Task[] $tasks
 */
class DictState extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dict_state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id'], 'integer'],
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
            'project_id' => Yii::t('dicts', 'Project'),
            'name' => Yii::t('dicts', 'Name'),
            'description' => Yii::t('dicts', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * Это очень тяжелый запрос, и в архитектуре он малоосмысленен
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['state' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\DictStateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\DictStateQuery(get_called_class());
    }
}
