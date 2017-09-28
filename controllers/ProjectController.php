<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 24.09.17
 * Time: 14:20
 */

namespace app\controllers;

use app\models\forms\DictStatesForm;
use Yii;
use yii\web\Controller;

class ProjectController extends Controller
{
    public $defaultAction = 'overview';
    public $layout = 'project';

    public function actionOverview()
    {
        $project = Yii::$app->projectService->project;
        return $this->render('overview', ['project' => $project]);
    }

    public function actionSettings()
    {
        $project = Yii::$app->projectService->project;
        $dictStatesForm = new DictStatesForm($project);

        if($dictStatesForm->load(Yii::$app->request->post()) && $dictStatesForm->validate()) {
            $dictStatesForm->save();
            return $this->refresh();
        }

        return $this->render('settings', [
            'project' => $project,
            'dictStatesForm' => $dictStatesForm,
        ]);
    }
}