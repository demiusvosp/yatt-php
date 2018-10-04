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
use yii\web\Response;
use yii\filters\AccessControl;
use app\base\BaseProjectController;
use app\components\auth\ProjectAccessRule;
use app\components\auth\Permission;
use app\models\entities\Task;
use app\models\forms\TaskForm;
use app\models\forms\TaskListForm;
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
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'class'   => ProjectAccessRule::class,
                        'project' => $this->project,
                        'actions' => ['list', 'view'],
                        'allow'   => true,
                    ],
                    [
                        'class'   => ProjectAccessRule::class,
                        'project' => $this->project,
                        'actions' => ['open'],
                        'roles'   => [Permission::OPEN_TASK],
                        'allow'   => true,
                    ],
                    [
                        'class'   => ProjectAccessRule::class,
                        'project' => $this->project,
                        'actions' => ['edit'],
                        'roles'   => [Permission::EDIT_TASK],
                        'allow'   => true,
                    ],
                    [
                        'class'   => ProjectAccessRule::class,
                        'project' => $this->project,
                        'actions' => ['change-stage'],
                        'roles'   => [Permission::CHANGE_STAGE],
                        'allow'   => true,
                        //'verbs'   => ['POST'],
                    ],
                    [
                        'class'   => ProjectAccessRule::class,
                        'project' => $this->project,
                        'actions' => ['close'],
                        'roles'   => [Permission::CLOSE_TASK],
                        'allow'   => true,
                        'verbs'   => ['POST'],
                    ],
                ],
            ],
        ];
    }


    public function actionList()
    {
        $taskListForm = new TaskListForm();
        return $this->render('list', [
            'taskListForm' => $taskListForm
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
