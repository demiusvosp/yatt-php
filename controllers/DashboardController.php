<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 11.11.16
 * Time: 12:19
 */

namespace app\controllers;

use yii\web\Controller;
use app\models\Project;

class DashboardController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $projects = Project::find()->orderBy('suffix')->all();

        return $this->render('index', [ 'projects'=>$projects ]);
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

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
}