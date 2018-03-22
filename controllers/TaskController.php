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
use yii\web\Response;
use yii\filters\AccessControl;
use app\base\BaseProjectController;
use app\components\auth\ProjectAccessRule;
use app\components\auth\Accesses;
use app\models\entities\Task;
use app\models\forms\TaskForm;
use app\models\forms\CloseTaskForm;
use app\models\queries\TaskQuery;


class TaskController extends BaseProjectController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'class'   => ProjectAccessRule::className(),
                        'project' => $this->project,
                        'actions' => ['list', 'view'],
                        'allow'   => true,
                    ],
                    [
                        'class'   => ProjectAccessRule::className(),
                        'project' => $this->project,
                        'actions' => ['open'],
                        'roles'   => [Accesses::OPEN_TASK],
                        'allow'   => true,
                    ],
                    [
                        'class'   => ProjectAccessRule::className(),
                        'project' => $this->project,
                        'actions' => ['edit'],
                        'roles'   => [Accesses::EDIT_TASK],
                        'allow'   => true,
                    ],
                    [
                        'class'   => ProjectAccessRule::className(),
                        'project' => $this->project,
                        'actions' => ['change-stage'],
                        'roles'   => [Accesses::CHANGE_STAGE],
                        'allow'   => true,
                        //'verbs'   => ['POST'],
                    ],
                    [
                        'class'   => ProjectAccessRule::className(),
                        'project' => $this->project,
                        'actions' => ['close'],
                        'roles'   => [Accesses::CLOSE_TASK],
                        'allow'   => true,
                        'verbs'   => ['POST'],
                    ],
                ],
            ],
        ];
    }


    public function actionList()
    {
        $query = Task::find()->andProject($this->project);
        $query->joinWith([
            'assigned' => function ($query) {
                $query->from(['assigned' => 'user']);
            },
        ]);
        $query->joinWith([
            'stage' => function ($query) {
                $query->from(['stage' => 'dict_stage']);
            },
        ]);
        $query->joinWith([
            'type' => function ($query) {
                $query->from(['type' => 'dict_type']);
            },
        ]);
        $query->joinWith([
            'category' => function ($query) {
                $query->from(['category' => 'dict_category']);
            },
        ]);
        $query->joinWith([
            'versionOpen' => function ($query) {
                $query->from(['versionOpen' => 'dict_version']);
            },
        ]);
        $query->joinWith([
            'versionClose' => function ($query) {
                $query->from(['versionClose' => 'dict_version']);
            },
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['name'] = [
            'asc'  => ['suffix' => SORT_ASC, 'index' => SORT_ASC],
            'desc' => ['suffix' => SORT_DESC, 'index' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['assigned'] = [
            'asc'  => ['assigned.username' => SORT_ASC],
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
            'asc'  => ['versionOpen.position' => SORT_ASC],
            'desc' => ['versionOpen.position' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['versionClose.name'] = [
            'asc'  => ['versionClose.position' => SORT_ASC],
            'desc' => ['versionClose.position' => SORT_DESC],
        ];

        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionOpen()
    {
        $task = new TaskForm();

        if ($task->load(Yii::$app->request->post()) && $task->save()) {
            return $this->redirect([
                'view',
                'index'  => $task->index,
                'suffix' => $this->project->suffix,
            ]);
        } else {
            if ($task->hasErrors()) {
                Yii::$app->session->addFlash('error', Yii::t('task', 'Error in create new task'));
            }

            return $this->render('open', [
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
            return $this->render('edit', [
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
     * @return Response
     */
    public function actionChangeStage($suffix, $index, $stage)
    {
        $task = TaskQuery::getByIndex($suffix, $index);

        $task->changeStage($stage);
        $task->save();

        return $this->redirect(['view', 'index' => $task->index, 'suffix' => $task->suffix]);
    }


    /**
     * Закрыть задачу
     *
     * @return Response
     */
    public function actionClose()
    {
        $form = new CloseTaskForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $task = Task::findOne($form->task_id);
            $form->saveComment();

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
