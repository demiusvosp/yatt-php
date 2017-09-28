<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 28.09.17
 * Time: 14:43
 */

namespace app\models\forms;

use app\models\entities\DictState;
use Yii;
use yii\base\Model;

use app\components\ProjectService;
use app\models\entities\Project;

class DictStatesForm extends Model
{
    public $states = [];

    private $_project;

    public function __construct(Project $project = null, array $config = [])
    {
        if(! $project) {
            $this->_project = Yii::$app->projectService->project;
        }
        $this->_project = $project;
        parent::__construct($config);
    }

    public function init()
    {
        parent::init();
        $states = $this->_project->states;

        foreach ($states as $state) {
            $this->states[] = $state;
        }
        // и новая для создания нового занчения
        $newState = new DictState();
        $newState->project_id = $this->_project->id;
        $this->states[] = $newState;
    }

    public function load($data, $formName = null)
    {
        return Model::loadMultiple($this->states, $data, $formName);
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        $newState = end($this->states);
        if( !$newState->validate($attributeNames, $clearErrors)) {
            // последний элемент это новая запись в словарь. Если она не прошла валидацию, значит не надо её добавлять
            array_pop($this->states);
        }
        return Model::validateMultiple($this->states, $attributeNames);
    }

    public function save()
    {
        /** @var DictState $state */
        foreach ($this->states as $state) {
            $state->save(false);
        }
    }
}