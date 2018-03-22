<?php
/**
 * User: demius
 * Date: 19.11.17
 * Time: 11:27
 */

namespace app\widgets;


use yii\base\Widget;
use yii\caching\TagDependency;
use app\helpers\CacheTagHelper;
use app\helpers\ProjectUrl;
use app\models\entities\Project;
use app\models\entities\Task;
use app\models\queries\TaskStatsQuery;
use app\models\entities\DictVersion;


class ProjectTile extends Widget
{
    /** @var  Project */
    public $project;

    /** @var bool куда ссылается блок. true - на проект, false - не ссылается, url - на указанный url */
    public $link = true;

    /** @var  string заголовок окна */
    public $caption;

    /** @var array дополнительные html-аттрибуты таге контейнера виджета */
    public $options = ['class' => 'box-bordered box-success'];

    /** @var int Количество последних задач 0 - не отображать, -1 - отображать все */
    public $lastTasksNum = 0;

    /** @var int Количество прошедших версий 0 - не отображать, -1 - отображать все */
    public $closedVersionNum = -1;

    /** @var int Количество будующих версий 0 - не отображать, -1 - отображать все */
    public $openVersionNum = 4;


    public function init()
    {
        if ($this->link === true) {
            $this->link = ProjectUrl::to(['project/overview', 'project' => $this->project]);
        }
        if (!$this->caption) {
            $this->caption = $this->project->name;
        }
        // мержим необходимые виджету классы со стандартными или кастомными
        $this->options['class'] = 'box project-tile ' . $this->options['class'];

        parent::init();
    }


    public function run()
    {
        return $this->render('projectTile', [
            'project' => $this->project,
            'caption' => $this->caption,
            'link'    => $this->link,
            'options' => $this->options,

            'taskStat' => $this->getTasksStats(),
            'lastTasks' => ($this->lastTasks) ? $this->getLastTasks() : null,
        ]);
    }


    /**
     * Базовая статистика задач
     * @return array
     */
    public function getTasksStats()
    {
        $taskStat = [
            'total'    => TaskStatsQuery::statAllTasks($this->project),
            'open'     => TaskStatsQuery::statOpenTasks($this->project),
            'progress' => round(TaskStatsQuery::statTasksProgress($this->project)),
            'versions' => [],
        ];
        // css-классы отображаемых версий
        $versionTypes = [
            DictVersion::PAST => 'past',
            DictVersion::CURRENT => 'current',
            DictVersion::FUTURE => 'future'
        ];

        if($this->closedVersionNum != 0) {
            // Прошедшие версии (чтобы не было обидно релизить и закрывтаь версию (а когда их много?)
            $query = $this->project->getVersions()->andPast();
            $query->cache(0, new TagDependency(['tags' => CacheTagHelper::projectVersions($this->project->suffix)]));
            if($this->closedVersionNum >= 1) {
                $query->limit($this->closedVersionNum);
            }

            foreach ($query->all() as $version) {
                $taskStat['versions'][] = [
                    'name'     => $version->name,
                    'type'     => $versionTypes[$version->type],
                    'progress' => 100,
                ];
            }
        }

        if($this->openVersionNum != 0) {
            // текущие и будующие версии, над которыми идет работа
            $query = $this->project->getVersions()->andForClose();
            $query->cache(0, new TagDependency(['tags' => CacheTagHelper::projectVersions($this->project->suffix)]));
            if($this->openVersionNum >= 1) {
                $query->limit($this->openVersionNum);
            }

            foreach ($query->all() as $version) {
                $taskStat['versions'][] = [
                    'name'     => $version->name,
                    'type'     => $versionTypes[$version->type],
                    'progress' => round(TaskStatsQuery::statVersionProgress($this->project, $version->id))
                ];
            }
        }

        return $taskStat;
    }


    /**
     * Последние открытые задачи
     * @return Task[]|array
     */
    public function getLastTasks()
    {
        if($this->lastTasksNum != 0) {
            $query = Task::find()->andProject($this->project)->andOpen()->orderBy('updated_at DESC');
            // это задачи по которым идет активнейшая работа, лучше не кешировать
            if($this->lastTasksNum >= 1) {
                $query->limit($this->lastTasksNum);
            }
            return $query->all();
        }

        return [];
    }
}
