<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 15.10.17
 * Time: 14:55
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\ProjectService;
use app\models\entities\Project;
use app\models\forms\DictForm;


class ProjectSettingsController extends Controller
{
    public $defaultAction = 'main';
    public $layout = 'project-settings';

    public function actionMain()
    {
        /** @var Project $project */
        $project = Yii::$app->projectService->project;
        return $this->render('main', ['project' => $project]);
    }

    public function actionStates()
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

        return $this->render('states', [
            'project' => $project,
            'dictForm' => $dictForm,
        ]);
    }

    /*
     * да они очень повторяются, будем вводить специальную функциональность справочников, посмотрим, как это исправить
     */
    public function actionTypes()
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

        return $this->render('types', [
            'project' => $project,
            'dictForm' => $dictForm,
        ]);
    }
}