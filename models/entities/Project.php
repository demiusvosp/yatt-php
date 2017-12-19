<?php

namespace app\models\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use app\helpers\Access;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\models\queries\ProjectQuery;
use app\helpers\EntityInitializer;


/**
 * This is the model class for table "project".
 *
 * @property integer          $id
 * @property string           $suffix
 * @property string           $name
 * @property string           $description
 * @property integer          $public
 * @property string           $config - json настроек, хранящеся в БД
 * @property integer          $admin_id
 * @property integer          $last_task_index
 * @property DictStage[]      $stages
 * @property DictType[]       $types
 * @property DictVersion[]    $versions
 * @property DictDifficulty[] $difficulties
 * @property DictCategory[]   $categories
 *
 * @property User             $admin
 */
class Project extends ActiveRecord
{

    /**
     * Публичность проекта
     * @TODO продумать, может не хранить тут, а выдавать роль <project>_VIEW кому надо
     */

    /** Все. (в том числе гости) */
    const STATUS_PUBLIC_ALL = 0;
    /** Все зарегистрированные */
    const STATUS_PUBLIC_REGISTED = 1;
    /** Уполномоченные */
    const STATUS_PUBLIC_AUTHED = 2;

    /** @var array Конфигурация проекта (отдельная переменная, так как нельзя обращаться к вирутальному полю, как к массиву) */
    public $configuration = [];


    public function getPublicStatusName()
    {
        return ArrayHelper::getValue(self::getPublicStatusesArray(), $this->public);
    }


    public static function getPublicStatusesArray()
    {
        return [
            self::STATUS_PUBLIC_AUTHED   => Yii::t('project', 'Empowered'),// 'Уполномоченные',
            self::STATUS_PUBLIC_REGISTED => Yii::t('project', 'Registered'),
            self::STATUS_PUBLIC_ALL      => Yii::t('project', 'All'),
        ];
    }


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
            [['suffix', 'admin_id'], 'required'], // обязательные

            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['public', 'admin_id'], 'integer'],

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

            // связь админ проекта
            [
                ['admin_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => User::className(),
                'targetAttribute' => ['admin_id' => 'id'],
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
            'public'      => Yii::t('project', 'Public'),
            'admin_id'    => Yii::t('project', 'Project admin'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(User::className(), ['id' => 'admin_id']);
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
        if (ctype_digit($value)) {// аналог regex:\d+
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

        if (array_key_exists('admin_id', $changedAttributes)) {
            // у проекта поменялся админ. Меняем полномочия
            $auth = Yii::$app->get('authManager');
            if (isset($changedAttributes['admin_id'])) {
                $auth->revoke(Access::ADMIN, $changedAttributes['admin_id'], $this);
            }
            $auth->assign(Access::ADMIN, $this->admin_id, $this);
        }
        parent::afterSave($insert, $changedAttributes);
    }


    public function beforeDelete()
    {
        EntityInitializer::deinitializeProject($this);

        return parent::beforeDelete();
    }


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
     * @param string $name
     * @return mixed|null
     */
    public function getConfigItem($name)
    {
        return isset($this->configuration[$name]) ? $this->configuration[$name] : null;
    }


    /**
     * Установить настройку проекта
     * @param string $name
     * @param mixed $value
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

    public function canDelete()
    {
        if($this->getTasks()->count() > 0) {
            return false;
        }

        return true;
    }
}
