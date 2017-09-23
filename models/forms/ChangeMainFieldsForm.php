<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 14.11.16
 * Time: 22:37
 */

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\entities\User;

class ChangeMainFieldsForm extends Model
{

    public $username;
    public $email;

    /** @var User user */
    public $user;


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'E-mail'
        ];
    }

    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#i'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'validateUsername'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
        ];
    }


    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->user;

            $checkUser = User::findByUsername($this->username);
            if($checkUser && $checkUser->id != $user->id) {
                // нашелся юзер с таким username, но это не этот пользователь
                $this->addError($attribute, 'Пользователь с таким username уже существует');
            }
        }
    }


    public function save()
    {
        if ($this->validate()) {
            $user = $this->user;
            $user->username = $this->username;
            $user->email = $this->email;
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