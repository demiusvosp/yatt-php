<?php
/**
 * User: demius
 * Date: 19.11.17
 * Time: 11:27
 */

namespace app\widgets;


use app\models\entities\DictVersion;
use yii\jui\Widget;
use app\helpers\ProjectUrl;
use app\models\entities\Project;
use app\models\queries\TaskStatsQuery;


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
        // Прошедшие версии (чтобы не было обидно релизить и закрывтаь версию)
        $versions = $this->project->getVersions()->andPast()->all();
        foreach ($versions as $version) {
            $taskStat['versions'][] = [
                'name' => $version->name,
                'type' => $versionTypes[$version->type],
                'progress' => 100,
            ];
        }
        // текущие и будующие версии, над которыми идет работа
        $versions = $this->project->getVersions()->andForClose()->limit(4)->all();
        foreach ($versions as $version) {
            $taskStat['versions'][] = [
                'name' => $version->name,
                'type' => $versionTypes[$version->type],
                'progress' => round(TaskStatsQuery::statVersionProgress($version))
            ];
        }

        return $this->render('projectTile', [
            'project' => $this->project,
            'caption' => $this->caption,
            'link'    => $this->link,
            'options' => $this->options,

            'taskStat' => $taskStat,
        ]);
    }
}