<?php
/**
 * User: demius
 * Date: 18.11.17
 * Time: 16:21
 */

namespace tests\unit\fixtures;


use yii\test\ActiveFixture;
use app\models\entities\User;


class UserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\entities\User';

    public function load()
    {
        echo "generate test users\r\n";

        foreach ($this->getData() as $alias => $row) {
            $user = new User();
            $user->setAttributes($row);
            if(!$user->save()) {
                echo 'Error in save user ' . $alias;
            }
            $this->data[$alias] = array_merge($row, ['id'=>$user->id]);
        }
    }

    public function unload()
    {
        echo "delete test users \r\n";
        foreach ($this->getData() as $alias => $row) {
            $user = User::findOne(['email' => $row['email']]);
            if($user) {
                $user->delete();
            }
        }
    }
}