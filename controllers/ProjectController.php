<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 24.09.17
 * Time: 14:20
 */

namespace app\controllers;

use yii\filters\AccessControl;
use app\base\BaseProjectController;
use app\helpers\ProjectAccessRule;


class ProjectController extends BaseProjectController
{
    public $defaultAction = 'overview';


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
                        'actions' => ['overview'],
                        'allow'   => true,
                    ],
                ],
            ],
        ];
    }


    /**
     * Главная страница проекта
     * @return string
     */
    public function actionOverview()
    {
        return $this->render('overview', ['project' => $this->project]);
    }

}