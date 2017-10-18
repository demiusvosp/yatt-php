<?php

namespace app\models\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\models\queries\TaskQuery;


/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property string $suffix
 * @property integer $index
 * @property string $caption
 * @property string $description
 * @property integer $assigned_id
 * @property integer $priority
 * @property integer $progress
 * @property integer $dict_version_open_id
 * @property integer $dict_version_close_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Project $project
 * @property User    $assigned
 * @property DictStage   $stage
 * @property DictType    $type
 * @property DictVersion $versionClose
 * @property DictVersion $versionOpen
 * @property DictDifficulty $difficulty
 * @property DictCategory   $category
 */
class Task extends ActiveRecord
{
    const PRIORITY_UNKNOWN   = 0;
    const PRIORITY_CRITICAL  = 1;
    const PRIORITY_VERY_HIGH = 2;
    const PRIORITY_HIGH      = 3;
    const PRIORITY_MEDIUM    = 4;
    const PRIORITY_LOW       = 5;
    const PRIORITY_VERY_LOW  = 6;


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
            [
                [
                    'index',
                    'assigned_id',
                    'priority',
                    'progress',
                    'dict_stage_id',
                    'dict_type_id',
                    'dict_category_id',
                    'dict_version_open_id',
                    'dict_version_close_id',
                    'dict_difficulty_id'
                ],
                'integer'
            ],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['suffix'], 'string', 'max' => 8],
            [['caption'], 'string', 'max' => 300],
            [
                ['suffix'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Project::className(),
                'targetAttribute' => ['suffix' => 'suffix']
            ],
            [
                ['assigned_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['assigned_id' => 'id']
            ],
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
            'priority' => Yii::t('task', 'Priority'),
            'progress' => Yii::t('task', 'Progress'),
            'created_at' => Yii::t('task', 'Created'),
            'updated_at' => Yii::t('task', 'Updated'),
            'dict_stage_id' => Yii::t('dicts', 'Stage'),
            'dict_type_id' => Yii::t('dicts', 'Type'),
            'dict_version_open_id' => Yii::t('dicts', 'Open in version'),
            'dict_version_close_id' => Yii::t('dicts', 'Сoming in version'),
            'dict_difficulty_id' => Yii::t('dicts', 'Difficulty'),
            'dict_category_id' => Yii::t('dicts', 'Category'),
        ];
    }


    public static function priorityLabels()
    {
        return [
            static::PRIORITY_CRITICAL  => Yii::t('task', 'Critical'),
            static::PRIORITY_VERY_HIGH => Yii::t('task', 'Very high'),
            static::PRIORITY_HIGH      => Yii::t('task', 'High'),
            static::PRIORITY_MEDIUM    => Yii::t('task', 'Medium'),
            static::PRIORITY_LOW       => Yii::t('task', 'Low'),
            static::PRIORITY_VERY_LOW  => Yii::t('task', 'Very Low'),
        ];
    }


    public static function priorityStyles()
    {
        return [
            static::PRIORITY_UNKNOWN   => 'unknown',
            static::PRIORITY_CRITICAL  => 'critical',
            static::PRIORITY_VERY_HIGH => 'very_high',
            static::PRIORITY_HIGH      => 'high',
            static::PRIORITY_MEDIUM    => 'medium',
            static::PRIORITY_LOW       => 'low',
            static::PRIORITY_VERY_LOW  => 'very_low',
        ];
    }

    /**
     * @return string
     */
    public function getPriorityName()
    {
        if(!isset(static::priorityLabels()[$this->priority])) {
            return Yii::t('common', 'Not set');
        }
        return static::priorityLabels()[$this->priority];
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
     * @return \yii\db\ActiveQuery
     */
    public function getStage()
    {
        return $this->hasOne(DictStage::className(), ['id' => 'dict_stage_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(DictType::className(), ['id' => 'dict_type_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVersionOpen()
    {
        return $this->hasOne(DictVersion::className(), ['id' => 'dict_version_open_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVersionClose()
    {
        return $this->hasOne(DictVersion::className(), ['id' => 'dict_version_close_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDifficulty()
    {
        return $this->hasOne(DictDifficulty::className(), ['id' => 'dict_difficulty_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(DictCategory::className(), ['id' => 'dict_category_id']);
    }


    /**
     * @inheritdoc
     * @return \app\models\queries\TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskQuery(get_called_class());
    }


    public function getName()
    {
        return $this->suffix . '#' . $this->index;
    }

}
