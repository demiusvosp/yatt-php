<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 14.11.16
 * Time: 17:05
 */

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\entities\User;

class RegistrationForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $verifyCode;

    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#i'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'Данное имя пользователя уже используется'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],

            ['password', 'required'],
            ['password', 'string', 'min' => 3],

            ['verifyCode', 'captcha', 'captchaAction' => '/main/captcha'],
        ];
    }

    public function registration()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->status = User::STATUS_WAIT;
            $user->generateUserToken();

            if ($user->save()) {
                Yii::$app->mailer->compose('@app/mail/authentication/emailConfirm', ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Подтверждение email для ' . Yii::$app->name)
                    ->send();
                return $user;
            }
        }

        return null;
    }
}