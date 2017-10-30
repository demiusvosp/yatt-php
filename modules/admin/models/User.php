<?php
/**
 * User: demius
 * Date: 24.10.17
 * Time: 23:38
 */

namespace app\modules\admin\models;

use Yii;


class User extends \app\models\entities\User
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    public $password = '';


    public function rules()
    {
        return array_merge(parent::rules(), [
            ['password', 'string'],
            ['password', 'required', 'on' => static::SCENARIO_CREATE],
        ]);
    }


    public function scenarios()
    {
        $fields = ['username', 'email', 'status', 'password'];

        return [
            static::SCENARIO_CREATE  => $fields,
            static::SCENARIO_EDIT    => $fields,
            static::SCENARIO_DEFAULT => [],
        ];
    }


    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'password' => Yii::t('user', 'Password'),
        ]);
    }


    public function beforeSave($insert)
    {
        if (!empty($this->password)) {
            $this->setPassword($this->password);
            if (!$insert) {
                Yii::$app->session->addFlash('warning',
                    'You change user <b>\"' . $this->username . '\"</b> password');
            }
        } else {
            if ($insert) {
                throw new \InvalidArgumentException('Create user without password');
            }
        }

        return parent::beforeSave($insert);
    }
}