<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;
use app\models\forms\RegistrationForm;


class AuthorizationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login, logout, registration, confirmEmail'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login, registration, confirmEmail'],
                        'allow' => true,
                        'roles' => ['?'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if($model->login()) {
                return $this->goBack();
            }
        }
        return $this->render('login', [
            'model' => $model,
        ]);
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
            if($newUser = $model->registration()) {
                Yii::$app->getSession()->setFlash('success', $newUser->username . ' успешно зарегистрированн. Пожалуйста подтвердите email');
                return $this->goBack();
            }
        }
        return $this->render('registration', [
            'model' => $model,
        ]);
    }

    public function actionConfirmEmail($token)
    {
        if(empty($token) || !is_string($token)) {
            Yii::$app->getSession()->setFlash('error', ' Ошибка при получении токена подтверждения');
            return $this->goHome();
        }
        $user = User::findByUserToken($token);
        if(!$user) {
            Yii::$app->getSession()->setFlash('error', 'Токен подтверждения не найден');
            return $this->goHome();
        }
        if(! User::isUserTokenValid($token)) {
            Yii::$app->getSession()->setFlash('error', 'Токен подтверждения просрочен');
            return $this->goHome();
        }
        $user->confirmUser();
        $user->save();
        if(!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }
        Yii::$app->user->login($user);

        return $this->goHome();
    }
}
