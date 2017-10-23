<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 26.09.17
 * Time: 14:12
 */

namespace app\models\forms;

use Yii;
use app\helpers\EntityInitializer;
use app\components\ProjectService;
use app\models\entities\Task;


/**
 * обертка над моделью-кнтейнером, дающая весь дополнительны функционал, и скрывающая детали контейнера
 * Class taskCreateForm
 * @package app\models\forms
 */
class TaskForm extends Task
{
    /** @var ProjectService */
    protected $_projectService;

    public function rules()
    {
        $rules = parent::rules();

        return $rules;
    }

    public function init()
    {
        $this->_projectService = Yii::$app->projectService;
        parent::init();

        if($this->isNewRecord) {
            EntityInitializer::initializeTask($this, $this->_projectService->project);
        }
    }


    public function beforeSave($insert)
    {
        $this->index = $this->_projectService->generateNewTaskId();
        $this->suffix = $this->_projectService->project->suffix;

        return parent::beforeSave($insert);
    }

}