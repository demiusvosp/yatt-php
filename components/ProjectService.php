<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 24.09.17
 * Time: 3:07
 */

namespace app\components;

use Yii;
use yii\base\Component;
use yii\web\NotFoundHttpException;
use app\helpers\ProjectUrl;
use app\models\entities\Project;
use app\models\entities\DictStage;
use app\models\queries\DictVersionQuery;
use app\models\queries\ProjectQuery;

class ProjectService extends Component
{
    // Возможно закрыть от внешнего проект, и отдавтаь только нужные части.
    /** @var Project */
    public $project = null;

    /** @var array */
    public $projectMenu = [];

    public function init()
    {
        parent::init();
        $allProjects = ProjectQuery::allowProjectsList();

        if (count($allProjects) == 0) {
            $this->projectMenu[] = ['label' => Yii::t('common', 'Home'), 'url' => ['main/index']];

        } elseif (count($allProjects) == 1) {
            $this->project = ProjectQuery::allowProjectsQuery()->one();
            $this->projectMenu[] = [
                'label' => $this->project->name,
                'url' => ProjectUrl::to(['project/overview', 'project' => $this->project])
            ];

        } else {
            if (Yii::$app->request->get('suffix')) {
                $this->project = Project::findOne(Yii::$app->request->get('suffix'));
            }

            $projectItems = [];
            foreach ($allProjects as $project) {
                $projectItems[] = [
                    'label' => $project->name,
                    'url' => ProjectUrl::to(['project/overview', 'project' => $project])
                ];
            }
            $projectItems[] = '<li class="divider"></li>';
            $projectItems[] = ['label' => Yii::t('common', 'Home'), 'url' => ['main/index']]; //вобще это скорее страница все проекты

            $this->projectMenu[] = [
                'label' => Yii::t('project', 'Projects'),
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
        if (!$this->project) {
            throw new NotFoundHttpException(Yii::t('project', 'Project not found'));
        }
        return $this->project;
    }

    /**
     * вернуть суффикс проекта для урла. здесь не удобно
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

    /**
     * Получить справочники этапов задачи
     * @return array
     */
    public function getStagesList()
    {
        $list = [];
        /** @var DictStage $stage */
        foreach ($this->project->stages as $stage) {
            if ($stage->isClose()) {// нельзя выбрать этап закрыта, надо закрывать кнопкой.
                continue;
            }
            $list[$stage->id] = $stage->name;
        }
        return $list;
    }


    /**
     * Получить справочники типов задачи
     * @return array
     */
    public function getTypesList()
    {
        $list = [];
        foreach ($this->project->types as $type) {
            if (!$type) break;

            $list[$type->id] = $type->name;
        }
        return $list;
    }


    /**
     * Список версий проекта.
     * @param $open true - те в которых можно открывать задачи, false - в которых можно закрывать
     * @return array
     */
    public function getVersionList($open)
    {
        $list = [];
        /** @var DictVersionQuery $query */
        $query = $this->project->getVersions();
        if ($open) {
            $query->andForOpen();
        } else {
            $query->andForClose();
        }
        $versions = $query->all();

        foreach ($versions as $version) {
            if (!$version) break;

            $list[$version->id] = $version->name;
        }
        return $list;
    }


    /**
     * Получить справочники трудоемкости задачи
     * @return array
     */
    public function getDifficultyList()
    {
        $list = [];
        foreach ($this->project->difficulties as $level) {
            if (!$level) break;

            $list[$level->id] = $level->name;
        }
        return $list;
    }


    /**
     * Получить справочники категорий задачи
     * @return array
     */
    public function getCategoryList()
    {
        $list = [];
        foreach ($this->project->categories as $category) {
            if (!$category) break;

            $list[$category->id] = $category->name;
        }
        return $list;
    }

}
