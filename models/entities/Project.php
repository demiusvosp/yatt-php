<?php

namespace app\models\entities;

use yii\db\ActiveRecord;
use app\models\User;
use app\models\queries\ProjectQuery;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property string $suffix
 * @property string $name
 * @property string $description
 * @property integer $public
 * @property string $config
 * @property integer $admin_id
 *
 * @property User $admin
 */
class Project extends ActiveRecord
{
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
            'public' => '0-только уполномоченным, 1-только зарегистрированным, 2-всем',
            'config' => 'прочий конфиг',
            'admin_id' => 'основной админ проекта',
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
     * @return \app\models\queries\ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
    }
}
