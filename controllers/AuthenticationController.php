<?php

namespace app\controllers;

use Yii;
use yii\helpers\Html;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\entities\User;
use app\models\forms\LoginForm;
use app\models\forms\RegistrationForm;


class AuthenticationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['login, logout, registration, confirmEmail, profile'],
                'rules' => [
                    [
                        'actions' => ['login, registration, confirmEmail'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                        'verbs'    => ['POST'],
                    ],
                ],
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            if(Yii::$app->request->isAjax) {
                return $this->asJson(['success' => true]);
            }
            return $this->goBack();
        }

        $model = new LoginForm();
        if(Yii::$app->request->isAjax) {

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                return $this->asJson(['success' =>$model->login()]);
            }
            $result = [];
            foreach ($model->getErrors() as $attribute => $errors) {
                $result[Html::getInputId($model, $attribute)] = $errors;
            }

            return $this->asJson(['success' => false, 'errors' => $result]);

        } else {
            if ($model->load(Yii::$app->request->post())) {

                if ($model->validate()) {
                    $model->login();

                    return $this->goHome();
                }
            }

            $this->layout = 'login-page';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionRegistration()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegistrationForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($newUser = $model->registration()) {
                Yii::$app->getSession()->setFlash('success',
                    $newUser->username . ' успешно зарегистрированн. Пожалуйста подтвердите email');

                return $this->goBack();
            }
        }

        return $this->render('registration', [
            'model' => $model,
        ]);
    }


    public function actionConfirmEmail($token)
    {
        if (empty($token) || !is_string($token)) {
            Yii::$app->getSession()->setFlash('error', ' Ошибка при получении токена подтверждения');

            return $this->goHome();
        }
        $user = User::findByUserToken($token);
        if (!$user) {
            Yii::$app->getSession()->setFlash('error', 'Токен подтверждения не найден');

            return $this->goHome();
        }
        if (!User::isUserTokenValid($token)) {
            Yii::$app->getSession()->setFlash('error', 'Токен подтверждения просрочен');

            return $this->goHome();
        }
        $user->confirmUser();
        $user->save();
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }
        Yii::$app->user->login($user);

        return $this->goHome();
    }

}
