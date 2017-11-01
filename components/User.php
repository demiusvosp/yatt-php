<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 13:16
 */

namespace app\components;

use Yii;

class User extends \yii\web\User
{

    public function init()
    {
        parent::init();
        $this->accessChecker = Yii::$app->get('authManager');
    }
}