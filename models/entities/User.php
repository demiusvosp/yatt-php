<?php

namespace app\models\entities;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use app\models\queries\UserQuery;


/**
 * This is the model class for table "user".
 *
 * @property integer   $id
 * @property integer   $created_at
 * @property integer   $updated_at
 * @property string    $username
 * @property string    $auth_key
 * @property string    $user_token
 * @property string    $password_hash
 * @property string    $email
 * @property integer   $status
 *
 * @property Project[] $projects
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 0;
    const STATUS_WAIT = 1;
    const STATUS_BLOCKED = 2;


    // здесь появятся еще варианты, типа пользователь в процессе авторизации через OAuth


    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }


    public static function getStatusesArray()
    {
        return [
            self::STATUS_ACTIVE  => Yii::t('user', 'Active'), //'Активен',
            self::STATUS_WAIT    => Yii::t('user', 'Confirmation wait'), //'Ожидает подтверждения',
            self::STATUS_BLOCKED => Yii::t('user', 'Blocked'), // 'Заблокирован',
        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w\._-]+$#i'],
            [
                'username',
                'unique',
                'targetClass' => self::className(),
                'message'     => 'Данное имя пользователя уже используется',
            ],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => 'Данный email уже используется'],
            ['email', 'string', 'max' => 255],

            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_WAIT],
            //['status', 'in', 'range' => array_keys(self::getStatusesArray())], не даст юзать user из консоли. Переделать или убрать
        ];
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                  => 'ID',
            'created_at'          => Yii::t('user', 'Created at'), //'Создан',
            'updated_at'          => Yii::t('user', 'Updated at'), //'Обновлен',
            'username'            => Yii::t('user', 'Username'), //'Username',
            'auth_key'            => Yii::t('user', 'Auth key'), //'Auth Key',
            'email_confirm_token' => Yii::t('user', 'Email Confirmation Token'), //'Email Confirm Token',
            'password_hash'       => Yii::t('user', 'Password hash'), //'Пароль',
            'user_token'          => Yii::t('user', 'User token'), //'Токен подтверждения',
            'email'               => Yii::t('user', 'Email'), //'Email',
            'status'              => Yii::t('user', 'Status'), //'Статус',
        ];
    }


    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }


    /* IdentityInterface */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }


    public function getId()
    {
        return $this->getPrimaryKey();
    }


    public function getAuthKey()
    {
        return $this->auth_key;
    }


    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }


    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }


    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }


    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }


    /**
     * @param string $user_token
     * @return static|null
     */
    public static function findByUserToken($user_token)
    {
        return static::findOne(['user_token' => $user_token, 'status' => self::STATUS_WAIT]);
    }


    /**
     * Generates email confirmation token
     */
    public function generateUserToken()
    {
        $this->user_token = Yii::$app->security->generateRandomString() . '_' . time();
        $this->status = User::STATUS_WAIT;
    }


    public static function isUserTokenValid($token)
    {
        if (empty($token) || !is_string($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.torenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);

        return $timestamp + $expire >= time();
    }


    /**
     * Removes password reset token
     */
    public function confirmUser()
    {
        $this->user_token = null;
        $this->status = User::STATUS_ACTIVE;
    }


    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['admin_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if($insert) {
            $auth = Yii::$app->authManager;
            $user = $auth->getRole('user');
            $auth->assign($user, $this->id);
        }
        parent::afterSave($insert, $changedAttributes);
    }
}
