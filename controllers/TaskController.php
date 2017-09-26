<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 25.09.17
 * Time: 20:03
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use app\models\entities\Project;
use app\models\entities\Task;
use app\models\forms\TaskForm;

class TaskController extends Controller
{
    public $layout = 'project';


    public function actionList($suffix)
    {
        return $this->render('list');
    }

    public function actionCreate($suffix)
    {
        $model = new TaskForm();
        //$model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list', 'suffix' => Yii::$app->projectService->project->suffix]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionView($suffix, $no)
    {

    }


}