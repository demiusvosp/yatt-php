<?php
/**
 * User: demius
 * Date: 25.10.17
 * Time: 23:04
 */

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;


class BaseAdminControlller extends Controller
{
    public function beforeAction($action)
    {
        // в целом закрываем модуль от не root
        if(!Yii::$app->user || !Yii::$app->user->can('root')) {
            throw new ForbiddenHttpException();
        }
        return parent::beforeAction($action);
    }
}