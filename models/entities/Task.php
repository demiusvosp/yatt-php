<?php

namespace app\models\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use app\models\queries\TaskQuery;
use app\models\queries\DictStageQuery;


/**
 * This is the model class for table "task".
 *
 * @property integer        $id
 * @property string         $suffix
 * @property integer        $index
 * @property string         $caption
 * @property string         $description
 * @property integer        $assigned_id
 * @property integer        $priority
 * @property integer        $progress
 * @property integer        $difficulty_ratio
 * @property integer        $is_closed
 * @property integer        $close_reason
 * @property string         $created_at
 * @property string         $updated_at
 * @property integer        $dict_stage_id
 * @property integer        $dict_type_id
 * @property integer        $dict_version_open_id
 * @property integer        $dict_version_close_id
 * @property integer        $dict_difficulty_id
 * @property integer        $dict_category_id
 *
 * @property string         $name - имя(ID) задачи вида <project>#<index>
 * @property Project        $project
 * @property User           $assigned
 * @property DictStage      $stage
 * @property DictType       $type
 * @property DictVersion    $versionClose
 * @property DictVersion    $versionOpen
 * @property DictDifficulty $difficulty
 * @property DictCategory   $category
 */
class Task extends ActiveRecord implements IEditorType
{
    // Приоритеты задачи
    const PRIORITY_UNKNOWN = 0;
    const PRIORITY_CRITICAL = 1;
    const PRIORITY_VERY_HIGH = 2;
    const PRIORITY_HIGH = 3;
    const PRIORITY_MEDIUM = 4;
    const PRIORITY_LOW = 5;
    const PRIORITY_VERY_LOW = 6;

    // Причины закрытия
    const REASON_DONE = 0;
    const REASON_CANCEL = 1;
    const REASON_NOERROR = 2;
    const REASON_RETRY = 3;
    const REASON_IMPOSSIBLE = 4;


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
                    'dict_difficulty_id',
                ],
                'integer',
            ],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['suffix'], 'string', 'max' => 8],
            [['caption'], 'string', 'max' => 300],
            [
                ['suffix'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Project::className(),
                'targetAttribute' => ['suffix' => 'suffix'],
            ],
            [
                ['assigned_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => User::className(),
                'targetAttribute' => ['assigned_id' => 'id'],
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => Yii::t('task', 'ID'),
            'suffix'                => Yii::t('task', 'Suffix'),
            'index'                 => Yii::t('task', 'Index'),
            'caption'               => Yii::t('task', 'Caption'),
            'description'           => Yii::t('task', 'Description'),
            'assigned'              => Yii::t('task', 'Assigned'),
            'assigned_id'           => Yii::t('task', 'Assigned'),
            'priority'              => Yii::t('task', 'Priority'),
            'progress'              => Yii::t('task', 'Progress'),
            'created_at'            => Yii::t('task', 'Created'),
            'updated_at'            => Yii::t('task', 'Updated'),
            'stage'                 => Yii::t('dicts', 'Stage'),
            'dict_stage_id'         => Yii::t('dicts', 'Stage'),
            'type'                  => Yii::t('dicts', 'Type'),
            'dict_type_id'          => Yii::t('dicts', 'Type'),
            'versionOpen'           => Yii::t('dicts', 'Open in version'),
            'dict_version_open_id'  => Yii::t('dicts', 'Open in version'),
            'versionClose'          => Yii::t('dicts', 'Сoming in version'),
            'dict_version_close_id' => Yii::t('dicts', 'Сoming in version'),
            'difficulty'            => Yii::t('dicts', 'Difficulty'),
            'dict_difficulty_id'    => Yii::t('dicts', 'Difficulty'),
            'category'              => Yii::t('dicts', 'Category'),
            'dict_category_id'      => Yii::t('dicts', 'Category'),
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


    public static function reasonLabels()
    {
        return [
            static::REASON_DONE       => Yii::t('task', 'Done'),
            static::REASON_CANCEL     => Yii::t('task', 'Cancel'),
            static::REASON_NOERROR    => Yii::t('task', 'No error'),
            static::REASON_RETRY      => Yii::t('task', 'Retry'),
            static::REASON_IMPOSSIBLE => Yii::t('task', 'Impossible'),
        ];
    }


    /**
     * @return string
     */
    public function getPriorityName()
    {
        if (!isset(static::priorityLabels()[$this->priority])) {
            return Yii::t('common', 'Not set');
        }

        return static::priorityLabels()[$this->priority];
    }


    public function behaviors()
    {
        return [
            [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value'      => new Expression('NOW()'),
            ],
        ];
    }


    /**
     * Получить тип редактора поля (возможно стоит вынести в трейт для всех сущностей завязанных на проект)
     * @param string $field
     * @return mixed|null
     */
    public function getEditorType($field)
    {
        return $this->project->getConfigItem('editorType');
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
     * @param User $user
     */
    public function setAssigned($user)
    {
        $this->assigned_id = $user->id;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStage()
    {
        return $this->hasOne(DictStage::className(), ['id' => 'dict_stage_id']);
    }


    /**
     * @param DictStage $stage
     */
    public function setStage(DictStage $stage)
    {
        if ($stage->type == DictStage::TYPE_CLOSED) {
            $this->is_closed = true;
        } else {
            $this->is_closed = false;
        }
        $this->dict_stage_id = $stage->id;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(DictType::className(), ['id' => 'dict_type_id']);
    }


    /**
     * @param DictType| null $type
     */
    public function setType($type)
    {
        if($type) {
            $this->dict_type_id = $type->id;
        } else {
            $this->dict_type_id = null;
        }
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVersionOpen()
    {
        return $this->hasOne(DictVersion::className(), ['id' => 'dict_version_open_id']);
    }


    /**
     * @param DictVersion| null $version
     */
    public function setVersionOpen($version)
    {
        if($version) {
            $this->dict_version_open_id = $version->id;
        } else {
            $this->dict_version_open_id = null;
        }
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVersionClose()
    {
        return $this->hasOne(DictVersion::className(), ['id' => 'dict_version_close_id']);
    }


    /**
     * @param DictVersion| null $version
     */
    public function setVersionClose($version)
    {
        if($version) {
            $this->dict_version_close_id = $version->id;
        } else {
            $this->dict_version_close_id = null;
        }
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDifficulty()
    {
        return $this->hasOne(DictDifficulty::className(), ['id' => 'dict_difficulty_id']);
    }


    /**
     * @param DictDifficulty| null $difficulty
     */
    public function setDifficulty($difficulty)
    {
        if($difficulty) {
            $this->dict_difficulty_id = $difficulty->id;
            $this->difficulty_ratio   = $difficulty->ratio;
        } else {
            $this->dict_difficulty_id = null;
            $this->difficulty_ratio   = 1;
        }
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(DictCategory::className(), ['id' => 'dict_category_id']);
    }


    /**
     * @param DictCategory| null $category
     */
    public function setCategory($category)
    {
        if($category) {
            $this->dict_category_id = $category->id;
        } else {
            $this->dict_category_id = null;
        }
    }



    /**
     * @inheritdoc
     * @return \app\models\queries\TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskQuery(get_called_class());
    }


    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        // обновим денормальзованную сложность из справочника
        if($this->difficulty) {
            $this->difficulty_ratio = $this->difficulty->ratio;
        } else {
            $this->difficulty_ratio = 1;
        }
        return parent::beforeSave($insert);
    }


    /**
     * имя(ID) задачи вида <project>#<index>
     * @return string
     */
    public function getName()
    {
        return $this->suffix . '#' . $this->index;
    }


    /**
     * Полное имя задачи
     * @return string
     */
    public function getFullName()
    {
        return $this->getName() . ' - ' . $this->caption;
    }


    /**
     * Закрыть задачу
     * И вот почти на 300 строке первая функция бизнес-логики.
     *
     * @param $reason
     */
    public function close($reason)
    {
        $this->progress     = 100;
        $this->close_reason = $reason;
        $this->stage        = DictStageQuery::closed($this->project);

        $this->save();
    }


    /**
     * @param int|DictStage $stage
     */
    public function changeStage($stage)
    {
        if (!$stage instanceof DictStage) {
            $stage = DictStage::findOne(['id' => $stage]);
        }
        $this->setStage($stage);
    }

}
