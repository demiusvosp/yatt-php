<?php
/**
 * User: demius
 * Date: 18.11.17
 * Time: 14:55
 */

use app\models\entities\User;

return [
    'alice' => [
        'username' => 'alice',
        'email' => 'alice@example.com',
        'password_hash' => Yii::$app->security->generatePasswordHash('alice'),
        'status' => User::STATUS_ACTIVE
    ],
    'bob' => [
        'username' => 'bob',
        'email' => 'bob@example.com',
        'password_hash' => Yii::$app->security->generatePasswordHash('bob'),
        'status' => User::STATUS_ACTIVE
    ],
    'ivan' => [
        'username' => 'ivan',
        'email' => 'ivan@example.com',
        'password_hash' => Yii::$app->security->generatePasswordHash('ivan'),
        'status' => User::STATUS_ACTIVE
    ],
    'petr' => [
        'username' => 'petr',
        'email' => 'petr@example.com',
        'password_hash' => Yii::$app->security->generatePasswordHash('petr'),
        'status' => User::STATUS_ACTIVE
    ]
];