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
use app\models\entities\Task;
use app\models\forms\TaskForm;

class TaskController extends Controller
{
    public $layout = 'project';


    public function actionList()
    {
        $query = Task::find()->andProject(Yii::$app->projectService->project);
        $query->joinWith(['assigned' => function($query) { $query->from(['assigned' => 'user']); }]);
        $query->joinWith(['stage' => function($query) { $query->from(['stage' => 'dict_stage']); }]);
        $query->joinWith([ 'type' => function($query) { $query->from(['type' => 'dict_type']); }]);
        $query->joinWith(['category' => function($query) { $query->from(['category' => 'dict_category']); }]);
        $query->joinWith([ 'versionOpen' => function($query) { $query->from(['versionOpen' => 'dict_version']); }]);
        $query->joinWith([ 'versionClose' => function($query) { $query->from(['versionClose' => 'dict_version']); }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['assigned.username'] = [
            'asc' => ['assigned.username' => SORT_ASC],
            'desc' => ['assigned.username' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['stage.name'] = [
            'asc'  => ['stage.position' => SORT_ASC],
            'desc' => ['stage.position' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['type.name'] = [
            'asc'  => ['type.position' => SORT_ASC],
            'desc' => ['type.position' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['category.name'] = [
            'asc'  => ['category.position' => SORT_ASC],
            'desc' => ['category.position' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['versionOpen.name'] = [
            'asc'  => ['type.position' => SORT_ASC],
            'desc' => ['type.position' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['versionClose.name'] = [
            'asc'  => ['type.position' => SORT_ASC],
            'desc' => ['type.position' => SORT_DESC],
        ];

        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $task = new TaskForm();

        if ($task->load(Yii::$app->request->post()) && $task->save()) {
            return $this->redirect(['view', 'index' => $task->index, 'suffix' => Yii::$app->projectService->project->suffix]);
        } else {
            if($task->hasErrors()) {
                Yii::$app->session->addFlash('error', Yii::t('task', 'Error in create new task'));
            }
            return $this->render('create', [
                'task' => $task,
            ]);
        }
    }


    /**
     * @param $index
     * @return string|\yii\web\Response
     */
    public function actionEdit($index)
    {
        $task = Task::findOne(['index' => $index, 'suffix' => Yii::$app->get('projectService')->getSuffixUrl()]);

        if ($task->load(Yii::$app->request->post()) && $task->save()) {
            return $this->redirect(['view', 'index' => $task->index, 'suffix' => Yii::$app->projectService->project->suffix]);
        } else {
            return $this->render('create', [
                'task' => $task,
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
