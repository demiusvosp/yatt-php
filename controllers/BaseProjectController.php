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
use app\models\entities\Project;
use app\components\ProjectService;

class BaseProjectController extends Controller
{
    /** @var  ProjectService */
    public $projectService;
    /** @var  Project */
    public $project;

    public function beforeAction($action)
    {
        $this->projectService = Yii::$app->get('projectService');
        $this->project = $this->projectService->project;
        if(!$this->project) {
            throw new NotFoundHttpException(Yii::t('project', 'Project not found'));
        }
        return parent::beforeAction($action);
    }
}