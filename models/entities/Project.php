<?php

namespace app\models\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;
use app\helpers\EntityInitializer;
use app\models\queries\ProjectQuery;


/**
 * This is the model class for table "project".
 *
 * @property integer          $id
 * @property string           $suffix
 * @property string           $name
 * @property integer          $archived
 * @property string           $description
 * @property string           $config - json настроек, хранящеся в БД
 * @property integer          $last_task_index
 * @property DictStage[]      $stages
 * @property DictType[]       $types
 * @property DictVersion[]    $versions
 * @property DictDifficulty[] $difficulties
 * @property DictCategory[]   $categories
 *
 * @property User             $admin
 */
class Project extends ActiveRecord implements IEditorType
{

    /** @var array Конфигурация проекта (отдельная переменная, так как нельзя обращаться к вирутальному полю, как к массиву) */
    public $configuration = [];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['suffix', 'name'], 'required'], // обязательные

            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],

            // ограничения суффикса
            [['suffix'], 'string', 'min' => 1, 'max' => 8],
            [
                ['suffix'],
                'match',
                'pattern' => '/^[A-Z0-9]+$/i',
                'message' => Yii::t('project', 'suffix must contain only A-Z or 0-9 chars'),
            ],
            [
                ['suffix'],
                'unique',
                'message' => Yii::t('project', 'suffix must be unique'),
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'suffix'      => Yii::t('project', 'Suffix'),
            'name'        => Yii::t('project', 'Name'),
            'description' => Yii::t('project', 'Description'),
            'created_at'  => Yii::t('project', 'Created'),
            'updated_at'  => Yii::t('project', 'Updated'),
            'config'      => Yii::t('project', 'Configuration'),
        ];
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
        return $this->getConfigItem('editorType', Yii::$app->params['defaultEditor']);
    }


    /**
     * Получить бобъект Query
     *
     * @inheritdoc
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
    }


    /**
     * Перегружаем findOne, чтобы быстро брать проект по id или по суффиксу
     *
     * @param mixed $value
     * @return Project
     * @throws \InvalidArgumentException
     */
    public static function findOne($value)
    {
        if (is_numeric($value)) {// аналог regex:\d+
            // поиск по id
            $field = 'id';
        } elseif (is_string($value)) {
            // поиск по суффиксу
            $field = 'suffix';
        } else {
            throw new \InvalidArgumentException('only for id or suffix');
        }

        // парент возвращает ActiveRecord, но мы уверенны что реально объект класса Project (а вот как это описать хз)
        return parent::findOne([$field => $value]);
    }


    public function afterFind()
    {
        parent::afterFind();

        $this->configuration = Json::decode($this->config, true);
    }


    /**
     *
     * Обработка даных перед сохранением
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->suffix = strtoupper($this->suffix);
        $this->config = Json::encode($this->configuration);

        return parent::beforeSave($insert);
    }


    /**
     * Поддержка консистенции проекта после его создания/сохранения. Инициализирует проект
     *
     * @param bool  $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            // если новый проект, инициализируем его. Это необходимо в том числе для поддержки консистентности.
            // (права админу проекта)
            EntityInitializer::initializeProject($this, false);
        }

        parent::afterSave($insert, $changedAttributes);
    }


    public function beforeDelete()
    {
        EntityInitializer::deinitializeProject($this);

        return parent::beforeDelete();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['suffix' => 'suffix']);
    }


    /**
     * @return \app\models\queries\DictStageQuery
     */
    public function getStages()
    {
        return $this->hasMany(DictStage::className(), ['project_id' => 'id']);
    }


    /**
     * @return \app\models\queries\DictTypeQuery
     */
    public function getTypes()
    {
        return $this->hasMany(DictType::className(), ['project_id' => 'id']);
    }


    /**
     * @return \app\models\queries\DictVersionQuery
     */
    public function getVersions()
    {
        return $this->hasMany(DictVersion::className(), ['project_id' => 'id']);
    }


    /**
     * @return \app\models\queries\DictDifficultyQuery
     */
    public function getDifficulties()
    {
        return $this->hasMany(DictDifficulty::className(), ['project_id' => 'id']);
    }


    /**
     * @return \app\models\queries\DictCategoryQuery
     */
    public function getCategories()
    {
        return $this->hasMany(DictCategory::className(), ['project_id' => 'id']);
    }


    /**
     * Получать настройку проекта
     *
     * @param string $name
     * @param mixed|null $default
     * @return mixed|null
     */
    public function getConfigItem($name, $default = null)
    {
        return isset($this->configuration[$name]) ? $this->configuration[$name] : $default;
    }


    /**
     * Установить настройку проекта
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setConfigItem($name, $value)
    {
        $this->configuration[$name] = $value;
    }


    /**
     * Сгенерировать новый ид задачи, и запомнить состояние из проекта.
     */
    public function generateNewTaskIndex()
    {
        $lastIndex = $this->last_task_index;
        $this->updateCounters(['last_task_index' => 1]);

        return $lastIndex;
    }


    /**
     * Можно ли удалить проект
     * @return bool
     */
    public function canDelete()
    {
        if ($this->getTasks()->count() > 0) {
            return false;
        }

        return true;
    }
}
