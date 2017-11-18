<?php
/**
 * User: demius
 * Date: 18.11.17
 * Time: 14:55
 */

use app\models\entities\User;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'username' => $faker->userName,
    'email' => $faker->email,
    'password_hash' => Yii::$app->security->generatePasswordHash('123456'),
    'status' => User::STATUS_ACTIVE
];