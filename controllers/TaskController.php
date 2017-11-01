<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 25.09.17
 * Time: 20:03
 */

namespace app\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\data\ActiveDataProvider;
use app\helpers\Access;
use app\models\entities\Task;
use app\models\forms\TaskForm;
use app\models\forms\CloseTaskForm;
use app\models\queries\TaskQuery;


class TaskController extends BaseProjectController
{
    public $layout = 'project';


    public function actionList()
    {
        $query = Task::find()->andProject(Yii::$app->projectService->project);
        $query->joinWith([
            'assigned' => function ($query) {
                $query->from(['assigned' => 'user']);
            }
        ]);
        $query->joinWith([
            'stage' => function ($query) {
                $query->from(['stage' => 'dict_stage']);
            }
        ]);
        $query->joinWith([
            'type' => function ($query) {
                $query->from(['type' => 'dict_type']);
            }
        ]);
        $query->joinWith([
            'category' => function ($query) {
                $query->from(['category' => 'dict_category']);
            }
        ]);
        $query->joinWith([
            'versionOpen' => function ($query) {
                $query->from(['versionOpen' => 'dict_version']);
            }
        ]);
        $query->joinWith([
            'versionClose' => function ($query) {
                $query->from(['versionClose' => 'dict_version']);
            }
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['assigned.username'] = [
            'asc' => ['assigned.username' => SORT_ASC],
            'desc' => ['assigned.username' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['stage.name'] = [
            'asc' => ['stage.position' => SORT_ASC],
            'desc' => ['stage.position' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['type.name'] = [
            'asc' => ['type.position' => SORT_ASC],
            'desc' => ['type.position' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['category.name'] = [
            'asc' => ['category.position' => SORT_ASC],
            'desc' => ['category.position' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['versionOpen.name'] = [
            'asc' => ['type.position' => SORT_ASC],
            'desc' => ['type.position' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['versionClose.name'] = [
            'asc' => ['type.position' => SORT_ASC],
            'desc' => ['type.position' => SORT_DESC],
        ];

        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        if(!Yii::$app->user->can(Access::OPEN_TASK)) {
            throw new ForbiddenHttpException('Вам не разрешено создавать задачи в этом проекте');
        }

        $task = new TaskForm();

        if ($task->load(Yii::$app->request->post()) && $task->save()) {
            return $this->redirect([
                'view',
                'index' => $task->index,
                'suffix' => $this->project->suffix
            ]);
        } else {
            if ($task->hasErrors()) {
                Yii::$app->session->addFlash('error', Yii::t('task', 'Error in create new task'));
            }

            return $this->render('create', [
                'task' => $task,
            ]);
        }
    }


    /**
     * @param $suffix
     * @param $index
     * @return string|\yii\web\Response
     */
    public function actionEdit($suffix, $index)
    {
        $task = TaskQuery::getByIndex($suffix, $index);

        if ($task->load(Yii::$app->request->post()) && $task->save()) {
            return $this->redirect(['view', 'index' => $index, 'suffix' => $suffix]);
        } else {
            return $this->render('create', [
                'task' => $task,
            ]);
        }
    }


    /**
     * По будущем просмотр будет не сильно отличаться от редактирования, через ajax
     *
     * @param $suffix
     * @param $index
     * @return string
     */
    public function actionView($suffix, $index)
    {
        $task = TaskQuery::getByIndex($suffix, $index);

        return $this->render('view', ['task' => $task]);
    }


    /**
     * Закрыть задачу
     *
     * @return string
     */
    public function actionClose()
    {
        $form = new CloseTaskForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $task = TaskQuery::getByIndex($form->suffix, $form->index);

            $task->close($form->close_reason);
            Yii::$app->session->addFlash('success', 'Task closed');

            return $this->redirect(['view', 'index' => $task->index, 'suffix' => $task->suffix]);

        } else {
            Yii::$app->session->addFlash('error', 'Cannot close task');
            Yii::error($form->getErrors(), 'Task');

            // вобще эти гигантские строки надо заменить действиями по умолчанию и хелпером создающим урлы для редиректов
            return $this->redirect(['project/overview', 'suffix' => $this->project->suffix]);
        }

    }
}
