<?php

namespace app\models\entities;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dict_state".
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
class DictStage extends ActiveRecord implements IWithProject, IWithPosition
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
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'string', 'max' => 255],
            [['name'], 'required'],
            [['project_id'], 'integer'],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['position'], 'integer'],
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
     * Хотя по указанному справочнику задачи будут только в проекте, получить все ошибки, или все новые...
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['state' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\DictStageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\DictStageQuery(get_called_class());
    }
}
