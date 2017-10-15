<?php

namespace app\models\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property string $suffix
 * @property integer $index
 * @property string $caption
 * @property string $description
 * @property integer $assigned_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Project $project
 * @property User $assigned
 */
class Task extends ActiveRecord
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
            [['index', 'assigned_id'], 'integer'],
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
            'id' => Yii::t('task', 'ID'),
            'suffix' => Yii::t('task', 'Suffix'),
            'index' => Yii::t('task', 'Index'),
            'caption' => Yii::t('task', 'Caption'),
            'description' => Yii::t('task', 'Description'),
            'assigned_id' => Yii::t('task', 'Assigned'),
            'created_at' => Yii::t('task', 'Created'),
            'updated_at' => Yii::t('task', 'Updated'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['suffix' => 'suffix']);
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


    public function getName()
    {
        return $this->suffix . '#' . $this->index;
    }

}
