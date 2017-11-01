<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 15.10.17
 * Time: 14:55
 */

namespace app\controllers;

use Yii;
use app\components\AccessManager;
use app\helpers\Access;
use app\models\forms\DictForm;
use app\models\forms\DictStagesForm;
use yii\web\ForbiddenHttpException;


class ProjectSettingsController extends BaseProjectController
{
    public $defaultAction = 'main';
    public $layout = 'project-settings';


    public function beforeAction($action)
    {
        if (!Yii::$app->user->can(Access::ADMIN)) {
            throw new ForbiddenHttpException();
        }

        return parent::beforeAction($action);
    }


    public function actionMain()
    {
        /*
         * Вобще нельзя этому контроллеру вот так давать вгружать в модель проекта post.
         * но и вводить сенарии ради этого не хочется, модель проекта и так пухнет. Будем наследовать
         */
        if ($this->project->load(Yii::$app->request->post())) {
            if ($this->project->save()) {
                Yii::$app->getSession()->addFlash('info', Yii::t('common', 'Successful'));
            }
        }

        return $this->render('main', ['project' => $this->project]);
    }


    public function actionStages()
    {
        $dictForm = new DictStagesForm([
            'project'   => $this->project,
            'items'     => $this->project->stages,
            'itemClass' => 'app\models\entities\DictStage',
        ]);

        if ($dictForm->load(Yii::$app->request->post()) && $dictForm->validate()) {
            $dictForm->save();

            return $this->refresh();
        }

        return $this->render('stages', [
            'project'  => $this->project,
            'dictForm' => $dictForm,
        ]);
    }


    /*
     * да они очень повторяются, будем вводить специальную функциональность справочников, посмотрим, как это исправить
     */
    public function actionTypes()
    {
        $dictForm = new DictForm([
            'project'   => $this->project,
            'items'     => $this->project->types,
            'itemClass' => 'app\models\entities\DictType',
        ]);

        if ($dictForm->load(Yii::$app->request->post()) && $dictForm->validate()) {
            $dictForm->save();

            return $this->refresh();
        }

        return $this->render('types', [
            'project'  => $this->project,
            'dictForm' => $dictForm,
        ]);
    }


    public function actionVersions()
    {
        $dictForm = new DictForm([
            'project'   => $this->project,
            'items'     => $this->project->versions,
            'itemClass' => 'app\models\entities\DictVersion',
        ]);

        if ($dictForm->load(Yii::$app->request->post()) && $dictForm->validate()) {
            $dictForm->save();

            return $this->refresh();
        }

        return $this->render('versions', [
            'project'  => $this->project,
            'dictForm' => $dictForm,
        ]);
    }


    public function actionDifficulties()
    {
        $dictForm = new DictForm([
            'project'   => $this->project,
            'items'     => $this->project->difficulties,
            'itemClass' => 'app\models\entities\DictDifficulty',
        ]);

        if ($dictForm->load(Yii::$app->request->post()) && $dictForm->validate()) {
            $dictForm->save();

            return $this->refresh();
        }

        return $this->render('difficulties', [
            'project'  => $this->project,
            'dictForm' => $dictForm,
        ]);
    }


    public function actionCategories()
    {
        $dictForm = new DictForm([
            'project'   => $this->project,
            'items'     => $this->project->categories,
            'itemClass' => 'app\models\entities\DictCategory',
        ]);

        if ($dictForm->load(Yii::$app->request->post()) && $dictForm->validate()) {
            $dictForm->save();

            return $this->refresh();
        }

        return $this->render('categories', [
            'project'  => $this->project,
            'dictForm' => $dictForm,
        ]);
    }


    public function actionUsers()
    {
        /** @var AccessManager $auth */
        $auth = Yii::$app->get('authManager');
        $roles = $auth->getRolesByProject($this->project);

        return $this->render('users', [
            'project' => $this->project,
            'roles'   => $roles,
        ]);
    }

}
