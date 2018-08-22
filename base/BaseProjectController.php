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


/**
 * Class BaseProjectController
 *
 * @property Project $project - текущий проект
 */
class BaseProjectController extends Controller implements IInProject
{

    /** @var  Project - текущий проект. Забират геттером*/
    protected $_project = null;

    public $layout = 'project';


    /**
     * получить текущий проект
     *
     * @return Project
     * @throws NotFoundHttpException
     */
    public function getProject()
    {
        if(!$this->_project) {
            if (Yii::$app->request->get('suffix')) {
                $this->_project = Project::findOne(Yii::$app->request->get('suffix'));
            }

            if (!$this->_project) {
                throw new NotFoundHttpException(Yii::t('project', 'Project not found'));
            }
        }

        return $this->_project;
    }


    /**
     * @param string $view
     * @param array  $params
     * @return string
     */
    public function render($view, $params = [])
    {
        if(!isset($params['project'])) {
            $params = array_merge($params, ['project' => $this->project]);
        }

        return parent::render($view, $params);
    }
}
