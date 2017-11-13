<?php
/**
 * User: demius
 * Date: 02.11.17
 * Time: 21:08
 */

namespace app\controllers;


use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;
use app\models\entities\User;
use app\models\forms\ChangeMainFieldsForm;
use app\models\forms\ChangePasswordForm;
use app\models\queries\UserQuery;


class UserController extends Controller
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
                        'actions' => ['profile', 'find-for-choose'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /**
     * @TODO отрефакторить
     * @return string|\yii\web\Response
     */
    public function actionProfile()
    {
        // вобще это вроде должно было отсечься behavior'ом access, но что-то не вышло
        if(Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;

        $changePasswordForm = new ChangePasswordForm(['user' => $user]);
        if($changePasswordForm->load(Yii::$app->getRequest()->post()) && $changePasswordForm->validate()) {
            // юзер меняет пароль
            $changePasswordForm->save();
            Yii::$app->getSession()->addFlash('warning', 'Вы успешно поменяли пароль. Войдите с новым паролем');
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $changeMainFieldsForm = new ChangeMainFieldsForm(['user' => $user]);
        $changeMainFieldsForm->username = $user->username;
        $changeMainFieldsForm->email = $user->email;
        if($changeMainFieldsForm->load(Yii::$app->getRequest()->post()) && $changeMainFieldsForm->validate()) {
            $changeMainFieldsForm->save();
            Yii::$app->user->logout();
            return $this->goHome();
        }

        return $this->render('profile', [
            'changePasswordForm' => $changePasswordForm,
            'changeMainFieldsForm' => $changeMainFieldsForm,
        ]);
    }


    public function actionFindForChoose($query)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return UserQuery::findUserByNameMail($query);
    }

}
