<?php

namespace app\models\entities;

use SebastianBergmann\CodeCoverage\RuntimeException;
use Yii;

/**
 * This is the model class for table "task".
 *
 * @property string $suffix
 * @property integer $id
 * @property string $caption
 * @property string $description
 * @property integer $assigned_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Project $project
 * @property User $assigned
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['suffix', 'id'], 'required'],
            [['id', 'assigned_id'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['suffix'], 'string', 'max' => 8],
            [['caption'], 'string', 'max' => 300],
            [['suffix'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['suffix' => 'suffix']],
            [['assigned_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['assigned_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'suffix' => Yii::t('task', 'суффикс'),
            'id' => Yii::t('task', 'ID'),
            'caption' => Yii::t('task', 'Заголовок'),
            'description' => Yii::t('task', 'Описание'),
            'assigned_id' => Yii::t('task', 'Текущий пользователь'),
            'created_at' => Yii::t('task', 'Создана'),
            'updated_at' => Yii::t('task', 'Оновленна'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['suffix' => 'suffix']);
    }

    public function setProject(Project $project)
    {
        if($this->isNewRecord) {
            $this->suffix = $project->suffix;
        } else {
            if(Task::find()->where(['id' => $this->id, 'suffix' => $project->suffix])->exists()) {
                throw new RuntimeException('Task id collizion');
            }
            $this->suffix = $project->suffix;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssigned()
    {
        return $this->hasOne(User::className(), ['id' => 'assigned_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\TaskQuery(get_called_class());
    }
}
