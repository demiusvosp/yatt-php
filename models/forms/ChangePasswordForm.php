<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 14.11.16
 * Time: 20:57
 */

namespace app\models\forms;


use yii\base\Model;
use app\models\entities\User;

class ChangePasswordForm extends Model
{
    /** @var string Старый пароль */
    public $old_password;

    /** @var string  Новый пароль */
    public $new_password;

    /** @var string  подтверждение пароля */
    public $new_password_repeat;

    /** @var User user */
    public $user;


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'old_password' => 'Старый пароль',
            'new_password' => 'Новый пароль',
            'new_password_repeat' => 'Повторите пароль',
        ];
    }

    public function rules()
    {
        return [
            ['old_password', 'required'],
            ['old_password', 'match', 'pattern' => '#^[\w_-]+$#i'],
            ['old_password', 'validatePassword'],

            ['new_password', 'required'],
            ['new_password', 'match', 'pattern' => '#^[\w_-]+$#i'],

            ['new_password_repeat', 'required'],
            ['new_password_repeat', 'match', 'pattern' => '#^[\w_-]+$#i'],

            ['new_password', 'compare'],
            [ ['new_password_repeat'], 'compare', 'compareAttribute' => 'new_password'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->user;

            if (!$user || !$user->validatePassword($this->old_password)) {
                $this->addError($attribute, 'Старый пароль не верен');
            }
        }
    }

    public function save()
    {
        if(!$this->hasErrors()) {
            $this->user->setPassword($this->new_password);
            $this->user->save();
        }
    }
}