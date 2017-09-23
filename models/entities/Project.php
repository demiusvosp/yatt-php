<?php

namespace app\models\entities;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\models\queries\ProjectQuery;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property string $suffix
 * @property string $name
 * @property string $description
 * @property integer $public
 * @property array $config
 * @property integer $admin_id
 *
 * @property User $admin
 */
class Project extends ActiveRecord
{

    /** Публичность проекта (в будущем наверно это будет тоже через назначение ролей групам пользователей (в том числе группе гости) */
    /** Уполномоченные */
    const STATUS_PUBLIC_AUTHED = 0;
    /** Все зарегистрированные */
    const STATUS_PUBLIC_REGISTED = 1;
    /** Все. (в том числе гости) */
    const STATUS_PUBLIC_ALL = 2;

    public function getPublicStatusName()
    {
        return ArrayHelper::getValue(self::getPublicStatusesArray(), $this->public);
    }

    public static function getPublicStatusesArray()
    {
        return [
            self::STATUS_PUBLIC_AUTHED => 'Уполномоченные',
            self::STATUS_PUBLIC_REGISTED => 'Зарегистрированные',
            self::STATUS_PUBLIC_ALL => 'Все',
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
            [['description', 'config'], 'string'],
            [['public', 'admin_id'], 'integer'],
            [['suffix'], 'string', 'max' => 8],
            [['name'], 'string', 'max' => 255],
            [['suffix'], 'unique'],
            [['admin_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['admin_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'suffix' => 'суффикс',
            'name' => 'Имя',
            'description' => 'Описание',
            'public' => 'Опубликован для',
            'config' => 'конфигурация',
            'admin_id' => 'Администратор проекта',
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
     * @inheritdoc
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
    }


    /**
     * Обработка данных после загрузки
     */
    public function afterFind()
    {
        $this->config = Json::decode($this->config);

        parent::afterFind();
    }


    /**
     *
     * Обработка даных перед сохранением
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->config = Json::encode($this->config);

        return parent::beforeSave($insert);
    }
}
