<?php
/**
 * User: demius
 * Date: 13.11.17
 * Time: 23:11
 */

namespace app\controllers;


use app\models\entities\Project;
use Yii;
use yii\base\InvalidParamException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\RequestHelper;
use app\helpers\Access;
use app\helpers\HtmlBlock;
use app\components\AccessManager;
use app\models\entities\User;


class AccessController extends Controller
{

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

        /** @var AccessManager $auth */
        $auth = Yii::$app->authManager;

        // можно либо по полномочию редактирования полномочий, либо админу проекта
        if (!Yii::$app->user->can(Access::ACCESS_MANAGEMENT) &&
            !Yii::$app->user->can(Access::projectItem(Access::ADMIN, $projectSuffix))
        ) {
            throw new ForbiddenHttpException();
        }

        $project = Project::findOne($projectSuffix);
        if(!$project) {
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

        /** @var AccessManager $auth */
        $auth = Yii::$app->authManager;

        // можно либо по полномочию редактирования полномочий, либо админу проекта
        if (!Yii::$app->user->can(Access::ACCESS_MANAGEMENT) &&
            !Yii::$app->user->can(Access::projectItem(Access::ADMIN, $projectSuffix))
        ) {
            throw new ForbiddenHttpException();
        }

        $project = Project::findOne($projectSuffix);
        if(!$project) {
            throw new InvalidParamException('Cannot find project');
        }
        if($project->admin_id == $userId) {
            throw new \DomainException('Cannot remove project admin access. Use project admin');
        }

        if (!$auth->revoke($role, $userId, $projectSuffix)) {
            return $this->asJson(['success' => false])->setStatusCode(406);
        }
        $data = ['success' => true];

        return $this->asJson($data)->setStatusCode(202);
    }
}