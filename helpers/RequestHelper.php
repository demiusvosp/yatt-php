<?php
/**
 * User: demius
 * Date: 14.11.17
 * Time: 0:15
 */

namespace app\helpers;

use Yii;
use yii\base\InvalidParamException;

class RequestHelper
{
    /**
     * @param      $param
     * @param bool $required
     * @param mixed $default
     * @return mixed
     * @throws InvalidParamException
     */
    public static function post($param, $required = true, $default = null)
    {
        $value = Yii::$app->request->post($param, $default);
        if($required && $value === $default) {
            throw new InvalidParamException($param . ' required. ');
        }
        return $value;
    }
}