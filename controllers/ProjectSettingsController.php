<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 15.10.17
 * Time: 14:55
 */

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\assets\ProjectSettingsAsset;
use app\base\BaseProjectController;
use app\components\auth\AuthProjectManager;
use app\components\auth\Role;
use app\components\auth\Permission;
use app\components\auth\ProjectAccessRule;
use app\models\entities\Task;
use app\models\forms\DictEditForm;
use app\models\forms\DictEditStagesForm;
use app\models\forms\DictEditVersionForm;


class ProjectSettingsController extends BaseProjectController
{
    public $defaultAction = 'main';
    public $layout = 'project-settings';


    public function init()
    {
        ProjectSettingsAsset::register($this->view);
        parent::init();
    }


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
                        'actions' => ['main', 'stages', 'types', 'versions', 'difficulties', 'categories', 'users'],
                        'roles'   => [Permission::PROJECT_SETTINGS],
                        'allow'   => true,
                    ],
                ],
            ],
        ];
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
        $dictForm = new DictEditStagesForm([
            'project'       => $this->project,
            'items'         => $this->project->stages,
            'itemClass'     => 'app\models\entities\DictStage',
            'relatedFields' => [[Task::className(), 'dict_stage_id']],
        ]);

        if ($dictForm->load(Yii::$app->request->post()) && $dictForm->validate()) {
            $dictForm->save();

            return $this->refresh();
        }

        return $this->render('stages', [
            'dictForm' => $dictForm,
        ]);
    }


    /*
     * да они очень повторяются, будем вводить специальную функциональность справочников, посмотрим, как это исправить
     */
    public function actionTypes()
    {
        $dictForm = new DictEditForm([
            'project'       => $this->project,
            'items'         => $this->project->types,
            'itemClass'     => 'app\models\entities\DictType',
            'relatedFields' => [[Task::className(), 'dict_type_id']],
        ]);

        if ($dictForm->load(Yii::$app->request->post()) && $dictForm->validate()) {
            $dictForm->save();

            return $this->refresh();
        }

        return $this->render('types', [
            'dictForm' => $dictForm,
        ]);
    }


    public function actionVersions()
    {
        $dictForm = new DictEditVersionForm([
            'project'       => $this->project,
            'items'         => $this->project->versions,
            'itemClass'     => 'app\models\entities\DictVersion',
            'relatedFields' => [
                [Task::className(), 'dict_version_open_id'],
                [Task::className(), 'dict_version_close_id'],
            ],
        ]);

        if ($dictForm->load(Yii::$app->request->post()) && $dictForm->validate()) {
            $dictForm->save();

            return $this->refresh();
        }

        return $this->render('versions', [
            'dictForm' => $dictForm,
        ]);
    }


    public function actionDifficulties()
    {
        $dictForm = new DictEditForm([
            'project'       => $this->project,
            'items'         => $this->project->difficulties,
            'itemClass'     => 'app\models\entities\DictDifficulty',
            'relatedFields' => [[Task::className(), 'dict_difficulty_id']],
        ]);

        if ($dictForm->load(Yii::$app->request->post()) && $dictForm->validate()) {
            $dictForm->save();

            return $this->refresh();
        }

        return $this->render('difficulties', [
            'dictForm' => $dictForm,
        ]);
    }


    public function actionCategories()
    {
        $dictForm = new DictEditForm([
            'project'       => $this->project,
            'items'         => $this->project->categories,
            'itemClass'     => 'app\models\entities\DictCategory',
            'relatedFields' => [[Task::className(), 'dict_category_id']],
        ]);

        if ($dictForm->load(Yii::$app->request->post()) && $dictForm->validate()) {
            $dictForm->save();

            return $this->refresh();
        }

        return $this->render('categories', [
            'dictForm' => $dictForm,
        ]);
    }


    public function actionUsers()
    {
        /** @var AuthProjectManager $auth */
        $auth  = Yii::$app->get('authManager');
        $roles = $auth->getRolesByProject($this->project);
        $items = [];
        /** @var Role $role */
        foreach ($roles as $role) {
            // да, мы здесь делаем запрос в цикле. Сейчас это не критично, а когда это будет тормозить - будем грузить юзеров аяксом
            $items[] = [
                'role'  => $role,
                'users' => $auth->getUsersByRole($role->name),
            ];
        }

        return $this->render('users', [
            'items' => $items,
        ]);
    }

}
