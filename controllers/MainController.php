<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 11.11.16
 * Time: 12:19
 */

namespace app\controllers;

use app\models\queries\ProjectQuery;
use Yii;
use yii\web\Controller;
use app\models\forms\ContactForm;

class MainController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'test' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $projects = ProjectQuery::allowProjectsQuery()->all();

        return $this->render('index', ['projects' => $projects]);
    }


    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

}