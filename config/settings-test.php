<?php
/**
 * User: demius
 * Date: 19.03.18
 * Time: 22:32
 */

return [
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=yatt_tests',
        'username' => 'yatt',
        'password' => 'yatt',
        'charset' => 'utf8',
    ],
    'cache' => [
        'class' => 'yii\caching\FileCache',// пока оставим file cache, позже сделаем выбор memcache/redis
    ],
];