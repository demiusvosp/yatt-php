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
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

use app\models\entities\Project;
use app\models\entities\Task;
use app\models\forms\TaskForm;

class TaskController extends Controller
{
    public $layout = 'project';


    public function actionList()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Task::find(),
        ]);

        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new TaskForm();
        //$model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'index' => $model->index, 'suffix' => Yii::$app->projectService->project->suffix]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * По факту просмотр будет не сильно отличаться от редактирования, через ajax
     * @param $index
     * @return string
     */
    public function actionView($index)
    {
        $task = Task::findOne(['index' => $index, 'suffix' => Yii::$app->get('projectService')->getSuffixUrl()]);
        return $this->render('view', ['task' => $task]);
    }


}