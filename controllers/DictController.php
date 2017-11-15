<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 11.10.17
 * Time: 22:24
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use app\helpers\Access;


class DictController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['delete-item'],
                        'allow'   => true,
                        'roles'   => [Access::ADMIN],
                        'verbs'   => ['POST'],
                    ],
                ],
            ],
        ];
    }


    /**
     * Удалить справочник
     *
     * @return Response
     */
    public function actionDeleteItem()
    {
        if (Yii::$app->request->method != 'DELETE') {
            return Yii::$app->getResponse()->setStatusCode(500, 'Unavailable method');
        }

        $item_id = Yii::$app->request->post('item_id');
        $dict = Yii::$app->request->post('dict', null);
        if (is_numeric($item_id)) {
            $res = Yii::$app->db->createCommand()->delete($dict, ['id' => $item_id])->execute();

            return $this->asJson(['result' => $res])->setStatusCode(201);
        }

        return Yii::$app->getResponse()->setStatusCode(500, 'Invalid arguments');
    }
}