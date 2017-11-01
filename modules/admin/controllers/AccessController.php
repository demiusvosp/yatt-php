<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 22:09
 */

namespace app\modules\admin\controllers;


use app\models\queries\ProjectQuery;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use app\components\AccessManager;
use app\components\access\Role;


class AccessController extends Controller
{
    /** @var AccessManager */
    public $accessManager = null;


    public function init()
    {
        parent::init();
        $this->accessManager = Yii::$app->authManager;
    }


    public function beforeAction($action)
    {
        if (!Yii::$app->user->can('accessManagement')) {
            throw new ForbiddenHttpException();
        }

        return parent::beforeAction($action);
    }


    public function actionIndex()
    {
        $projects = ProjectQuery::allowProjectsList(true, 'suffix');

        // Поскольку роли получаются не из ar, здесь не будет выборок, а перебор полного списка
        //   Возможно в будующем мы отрефакторим AccessManager, выбросив из него authManager
        $list = [ -1 => [] ];
        foreach ($projects as $id => $project) {
            $list[$project['suffix']] = ['label' => $project['name']];
        }

        /** @var Role $role */
        foreach ($this->accessManager->getRoles() as $role) {
            if($role->isGlobal()) {
                $list[-1]['items'][] = $role;
            } else {
                $list[$role->getProject()]['items'][] = $role;
            }
        }

        return $this->render('list', [
            'list' => $list,
        ]);
    }
}