<?php
/**
 * User: demius
 * Date: 13.12.17
 * Time: 0:03
 */

namespace app\models\forms;


use app\models\queries\TaskQuery;


class DictEditVersionForm extends DictEditForm
{
    public $openedTaskCount = [];

    public function init()
    {
        parent::init();

        // посчитаем статистику открытых задач
        $this->openedTaskCount = TaskQuery::versionWithOpenedTaskCount($this->project);
    }

}
