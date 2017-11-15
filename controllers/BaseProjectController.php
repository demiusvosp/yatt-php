<?php
/**
 * User: demius
 * Date: 01.11.17
 * Time: 22:51
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\components\ProjectService;
use app\models\entities\Project;


class BaseProjectController extends Controller
{
    /** @var  ProjectService */
    public $projectService;
    /** @var  Project */
    public $project;

    public $layout = 'project';


    /**
     * Проверка и установка текущего проекта, с которым работают все страницы проекта
     * @param \yii\base\Action $action
     * @return bool
     * @throws NotFoundHttpException
     */
    public function beforeAction($action)
    {
        $this->projectService = Yii::$app->get('projectService');
        $this->project = $this->projectService->project;
        if (!$this->project) {
            throw new NotFoundHttpException(Yii::t('project', 'Project not found'));
        }

        return parent::beforeAction($action);
    }

}
