<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 24.09.17
 * Time: 3:07
 */

namespace app\components;

use yii\base\Component;
use Yii;

use app\models\entities\Project;
use app\models\queries\ProjectQuery;
use yii\web\NotFoundHttpException;

class ProjectService extends Component
{
    // Возможно закрыть от внешнего проект, и отдавтаь только нужные части.
    /** @var Project */
    public $project = null;

    /** @var array  */
    public $projectMenu = [];

    public function init()
    {
        parent::init();
        $allProjects = ProjectQuery::allowProjectsList();

        if(count($allProjects) == 0) {
            $this->projectMenu[] = ['label' => Yii::t('common', 'Home'), 'url' => ['main/index']];

        } elseif(count($allProjects) == 1) {
            $this->project = ProjectQuery::allowProjectsQuery()->one();
            $this->projectMenu[] = [
                'label' => $this->project->name,
                'url' => ['project/overview', 'suffix' => strtolower($this->project->suffix)]
            ];

        } else {
           if( isset(Yii::$app->request->queryParams['suffix'])) {
               $this->project = Project::findOne(Yii::$app->request->queryParams['suffix']);
           }
           if(!$this->project) {
               // тут будут другие способы получения текущего проекта.
           }

            $projectItems = [];
            foreach ($allProjects as $project) {
                $projectItems[] = [
                    'label' => $project->name,
                    'url' => ['project/overview', 'suffix' => $project->suffix]
                ];
            }
            $projectItems[] = '<li class="divider"></li>';
            $projectItems[] = ['label' => Yii::t('common', 'Home'), 'url' => ['main/index']]; //вобще это скорее страница все проекты

            $this->projectMenu = ['label' => Yii::t('project', 'Projects'),
                'url' => ['main/index'],
                'items' => $projectItems];
        }
    }


    /**
     * Получить проект. Если его нет в этом запросе, запрос выполнить невозможно
     * @return Project
     * @throws NotFoundHttpException
     */
    public function getProject()
    {
        if(!$this->project) {
            throw new NotFoundHttpException(Yii::t('project', 'Project not found'));
        }
        return $this->project;
    }

    /**
     * вернуть суффикс проекта для урла
     * @return string
     */
    public function getSuffixUrl()
    {
        return strtolower($this->project->suffix);
    }

    /**
     * Сгенерировать новый ид задачи, и запомнить состояние из проекта.
     */
    public function generateNewTaskId()
    {
        $last_id = $this->project->last_task_id;
        $this->project->updateCounters(['last_task_id' => 1]);
        return $last_id;
    }
}