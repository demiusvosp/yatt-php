<?php
/**
 * User: demius
 * Date: 01.11.17
 * Time: 22:51
 */

namespace app\base;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\entities\Project;


class BaseProjectController extends Controller
{

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
        if (Yii::$app->request->get('suffix')) {
            $this->project = Project::findOne(Yii::$app->request->get('suffix'));
        }

        if (!$this->project) {
            throw new NotFoundHttpException(Yii::t('project', 'Project not found'));
        }

        return parent::beforeAction($action);
    }


    public function render($view, $params = [])
    {
        if(!isset($params['project'])) {
            $params = array_merge($params, ['project' => $this->project]);
        }

        return parent::render($view, $params);
    }
}
