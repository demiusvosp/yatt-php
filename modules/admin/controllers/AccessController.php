<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 22:09
 */

namespace app\modules\admin\controllers;


use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\helpers\Access;
use app\components\AccessManager;
use app\components\access\Role;
use app\models\queries\ProjectQuery;


class AccessController extends Controller
{
    /** @var AccessManager */
    public $accessManager = null;


    public function init()
    {
        parent::init();
        $this->accessManager = Yii::$app->authManager;
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
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [Access::ACCESS_MANAGEMENT],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        /** @var AccessManager $auth */
        $auth = Yii::$app->authManager;

        $projects = ProjectQuery::allowProjectsList(true, 'suffix');

        // Поскольку роли получаются не из ar, здесь не будет выборок, а перебор полного списка
        //   Возможно в будующем мы отрефакторим AccessManager, выбросив из него authManager
        $list = [ -1 => [] ];
        foreach ($projects as $id => $project) {
            $list[$project['suffix']] = ['label' => $project['name']];
        }

        /** @var Role $role */
        foreach ($this->accessManager->getRoles() as $role) {
            $item = [
                'role' => $role,
                'users' => $auth->getUsersByRole($role->name)
            ];

            if($role->isGlobal()) {
                $list[-1]['items'][] = $item;
            } else {
                $list[$role->getProject()]['items'][] = $item;
            }
        }

        return $this->render('list', [
            'list' => $list,
        ]);
    }
}