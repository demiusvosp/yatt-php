<?php
/**
 * User: demius
 * Date: 26.11.17
 * Time: 20:57
 */

namespace app\widgets;

use Yii;
use yii\widgets\InputWidget;
use app\models\entities\Project;

class TextEditor extends InputWidget
{
    /** @var Project */
    public $project = null;

    protected $_editorType;

    public function init()
    {
        if(!$this->project) {
            $this->project = Yii::$app->get('projectService')->project;
        }
        $this->_editorType = $this->project->getConfigItem('editorType', Project::EDITOR_PLAIN);

        parent::init();
    }

    public function run()
    {
        if($this->_editorType == Project::EDITOR_WYSIWYG) {

        } elseif($this->_editorType == Project::EDITOR_MD) {

        } else {

        }

        return '';
    }
}