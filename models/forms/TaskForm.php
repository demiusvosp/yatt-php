<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 26.09.17
 * Time: 14:12
 */

namespace app\models\forms;

use app\helpers\EntityInitializer;
use app\helpers\ProjectHelper;
use app\models\entities\Task;
use yii\base\InvalidArgumentException;


/**
 * обертка над моделью-кнтейнером, дающая весь дополнительны функционал, и скрывающая детали контейнера
 * Class taskCreateForm
 * @package app\models\forms
 */
class TaskForm extends Task
{

    public function rules()
    {
        $rules = parent::rules();

        return $rules;
    }

    public function init()
    {
        parent::init();

        if($this->isNewRecord) {
            EntityInitializer::initializeTask($this, ProjectHelper::currentProject());
        }
    }


    public function beforeSave($insert)
    {
        if($insert) {
            $this->index  = $this->project->generateNewTaskIndex();
            $this->suffix = $this->project->suffix;
        }

        return parent::beforeSave($insert);
    }

}