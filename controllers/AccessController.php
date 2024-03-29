<?php
/**
 * User: demius
 * Date: 13.11.17
 * Time: 23:11
 */

namespace app\controllers;


use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\base\InvalidParamException;
use yii\web\ForbiddenHttpException;
use app\components\auth\AuthProjectManager;
use app\components\auth\Permission;
use app\components\auth\ProjectAccessRule;
use app\helpers\RequestHelper;
use app\helpers\HtmlBlock;
use app\models\entities\User;
use app\models\entities\Project;


class AccessController extends Controller
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
                        'project' => RequestHelper::post('project', false, null),
                        'actions' => ['assign-role', 'revoke-role'],
                        'allow'   => true,
                        'roles'   => [Permission::MANAGEMENT_ACCESS, Permission::PROJECT_SETTINGS],
                        'verbs'   => ['POST'],
                    ],
                ],
            ],
        ];
    }


    /**
     * @return Response
     * @throws ForbiddenHttpException
     */
    public function actionAssignRole()
    {
        $userId = RequestHelper::post('userId');
        $role = RequestHelper::post('role');
        $projectSuffix = RequestHelper::post('project', false, null);
        $html = RequestHelper::post('html', false, false);

        /** @var AuthProjectManager $auth */
        $auth = Yii::$app->authManager;

        // можно либо по полномочию редактирования полномочий, либо админу проекта
        if (!Yii::$app->user->can(Permission::MANAGEMENT_ACCESS) &&
            !Yii::$app->user->can(Permission::PROJECT_SETTINGS, $projectSuffix)
        ) {
            throw new ForbiddenHttpException();
        }

        $project = Project::findOne($projectSuffix);
        if (!$project) {
            throw new InvalidParamException('Cannot find project');
        }

        if (!$auth->assign($role, $userId, $projectSuffix)) {
            return $this->asJson(['success' => false])->setStatusCode(406);
        }
        $data = ['success' => true];

        if ($html) {
            $user = User::findOne(['id' => $userId]);
            $data['html'] = HtmlBlock::userItem($user);
        }

        return $this->asJson($data)->setStatusCode(202);
    }


    /**
     * убрать роль пользователя
     *
     * @return Response
     * @throws ForbiddenHttpException
     */
    public function actionRevokeRole()
    {
        $userId = RequestHelper::post('userId');
        $role = RequestHelper::post('role');
        $projectSuffix = RequestHelper::post('project', false, null);

        /** @var AuthProjectManager $auth */
        $auth = Yii::$app->authManager;

        // можно либо по полномочию редактирования полномочий, либо админу проекта
        if (!Yii::$app->user->can(Permission::MANAGEMENT_ACCESS) &&
            !Yii::$app->user->can(Permission::PROJECT_SETTINGS, $projectSuffix)
        ) {
            throw new ForbiddenHttpException();
        }

        $project = Project::findOne($projectSuffix);
        if (!$project) {
            throw new InvalidParamException('Cannot find project');
        }

        if (!$auth->revoke($role, $userId, $projectSuffix)) {
            return $this->asJson(['success' => false])->setStatusCode(406);
        }
        $data = ['success' => true];

        return $this->asJson($data)->setStatusCode(202);
    }
}