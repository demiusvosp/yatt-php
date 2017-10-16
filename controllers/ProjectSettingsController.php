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
        /*
         * Вобще нельзя этому контроллеру воттак давать вгружать в моель проекта post.
         * но и вводить сенарии ради этого не хочется, модель проекта и так пухнет. Будем наследовать
         */
        if ($project->load(Yii::$app->request->post())) {
            if($project->save()) {
                Yii::$app->getSession()->addFlash('info', Yii::t('common', 'Successful'));
            }
        }

        return $this->render('main', ['project' => $project]);
    }

    public function actionStages()
    {
        /** @var Project $project */
        $project = Yii::$app->projectService->project;
        $dictForm = new DictForm([
            'project' => $project,
            'items'     => $project->stages,
            'itemClass' => 'app\models\entities\DictStage',
        ]);

        if($dictForm->load(Yii::$app->request->post()) && $dictForm->validate()) {
            $dictForm->save();
            return $this->refresh();
        }

        return $this->render('stages', [
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
            'items'     => $project->types,
            'itemClass' => 'app\models\entities\DictType',
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


    public function actionVersions()
    {
        /** @var Project $project */
        $project = Yii::$app->projectService->project;
        $dictForm = new DictForm([
            'project' => $project,
            'items'     => $project->versions,
            'itemClass' => 'app\models\entities\DictVersion',
        ]);

        if($dictForm->load(Yii::$app->request->post()) && $dictForm->validate()) {
            $dictForm->save();
            return $this->refresh();
        }

        return $this->render('versions', [
            'project' => $project,
            'dictForm' => $dictForm,
        ]);
    }
}