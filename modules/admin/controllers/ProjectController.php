<?php

namespace app\modules\admin\controllers;

use app\helpers\EntityInitializer;
use Yii;
use app\models\entities\Project;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Project::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'project' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $project = new Project();
        $project->admin_id = Yii::$app->user->identity->getId();

        if ($project->load(Yii::$app->request->post()) && $project->save()) {
            EntityInitializer::initializeProject($project);

            return $this->redirect(['view', 'id' => $project->id]);
        } else {
            return $this->render('create', [
                'project' => $project,
            ]);
        }
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * Снаружи роутер ивеет вид pm/<id>/edit, но здесь сохранено каноничное для yii названия, для работы виджетов из
     * коробки
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $project = $this->findModel($id);

        if ($project->load(Yii::$app->request->post()) && $project->save()) {
            return $this->redirect(['view', 'id' => $project->id]);
        } else {
            return $this->render('edit', [
                'project' => $project,
            ]);
        }
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($project = Project::findOne($id)) !== null) {
            return $project;
        } else {
            throw new NotFoundHttpException(Yii::t('common', 'The requested {{object}} does not exist.', ['object' => 'Project']));
        }
    }
}
