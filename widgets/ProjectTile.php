<?php
/**
 * User: demius
 * Date: 19.11.17
 * Time: 11:27
 */

namespace app\widgets;


use yii\jui\Widget;
use app\helpers\ProjectUrl;
use app\models\entities\Project;


class ProjectTile extends Widget
{
    /** @var  Project */
    public $project;

    /** @var bool куда ссылается блок. true - на проект, false - не ссылается, url - на указанный url */
    public $link = true;

    /** @var  string заголовок окна */
    public $caption;

    /** @var array дополнительные html-аттрибуты таге контейнера виджета */
    public $options = [ 'class' => 'box-bordered box-success'];


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
        ]);
    }
}