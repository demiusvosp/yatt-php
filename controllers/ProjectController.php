<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 24.09.17
 * Time: 14:20
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;

use app\models\entities\Project;



class ProjectController extends Controller
{
    public $defaultAction = 'overview';
    public $layout = 'project';

    public function actionOverview()
    {
        $project = Yii::$app->projectService->project;
        return $this->render('overview', ['project' => $project]);
    }

}