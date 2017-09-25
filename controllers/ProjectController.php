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
use yii\web\NotFoundHttpException;

class ProjectController extends Controller
{
    public $defaultAction = 'overview';
    public $layout = 'project';

    public function actionOverview($suffix)
    {
        $project = Project::findOne($suffix);
        if(!$project) {
            throw new NotFoundHttpException(Yii::t('project', 'Project not found'));
        }
        return $this->render('overview', ['project' => $project]);
    }
}