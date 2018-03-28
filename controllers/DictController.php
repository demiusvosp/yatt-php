<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 11.10.17
 * Time: 22:24
 */

namespace app\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\Response;
use yii\filters\AccessControl;
use app\base\BaseProjectController;
use app\components\auth\Accesses;
use app\components\auth\ProjectAccessRule;
use app\models\entities\DictVersion;
use app\models\forms\DictForm;


class DictController extends BaseProjectController
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
                        'class'   => ProjectAccessRule::className(),
                        'project' => $this->project,
                        'actions' => ['delete'],
                        'allow'   => true,
                        'roles'   => [Accesses::PROJECT_SETTINGS],
                        'verbs'   => ['DELETE'],
                    ],
                    [
                        'class'   => ProjectAccessRule::className(),
                        'project' => $this->project,
                        'actions' => ['past'],
                        'allow'   => true,
                        'roles'   => [Accesses::PROJECT_SETTINGS],
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
    public function actionDelete()
    {
        $dictForm = new DictForm();
        if($dictForm->load(Yii::$app->request->post(), '') && $dictForm->validate()) {

            $dict = $dictForm->getDict();
            $res = $dict->delete();

            return $this->asJson(['result' => $res])->setStatusCode(201);
        }

        return $this
            ->asJson(['result'=> false, 'errors' => $dictForm->getErrors()])
            ->setStatusCode(500, 'Invalid arguments');
    }


    /**
     * Отметить версию, как пройденную
     *
     * @return Response
     */
    public function actionPast()
    {
        $dictForm = new DictForm();
        if($dictForm->load(Yii::$app->request->post(), '') && $dictForm->validate()) {
            $dict = $dictForm->getDict();
            if(!$dict instanceof DictVersion) {
                throw new InvalidParamException('Cannot set past at not DictVersion');
            }
            if(!$dict->canChangeType(DictVersion::PAST)) {
                throw new \DomainException('Cannot set this version as past');
            }

            $dict->type = DictVersion::PAST;
            $res = $dict->save();

            return $this->asJson(['result' => $res])->setStatusCode(202);
        }

        return Yii::$app->getResponse()->setStatusCode(500, 'Invalid arguments');
    }
}