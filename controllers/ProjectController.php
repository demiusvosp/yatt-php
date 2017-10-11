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
use app\models\forms\DictForm;


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
        /** @var Project $project */
        $project = Yii::$app->projectService->project;
        $dictForm = new DictForm([
            'project' => $project,
            'items'     => $project->states,
            'itemClass' => 'app\models\entities\DictState',
        ]);

        if($dictForm->load(Yii::$app->request->post()) && $dictForm->validate()) {
            $dictForm->save();
            return $this->refresh();
        }

        return $this->render('settings', [
            'project' => $project,
            'dictForm' => $dictForm,
        ]);
    }
}