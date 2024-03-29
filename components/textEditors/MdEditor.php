<?php
/**
 * Lepture Markdown Editor class file
 *
 * @author Evgeniy Kuzminov
 * @license MIT License
 * http://stdout.in
 */

namespace app\components\textEditors;


use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;
use app\assets\MdEditorAsset;


class MdEditor extends InputWidget implements ITextEditor
{
    /**
     * Markdown options you want to override
     * See https://github.com/lepture/editor
     *
     * @var array
     */
    public $leptureOptions = [];
    /**
     * Marked options (markdown parser used by EpicEditor)
     * See details https://github.com/chjj/marked
     *
     * @var array
     */
    public $markedOptions = [];
    /**
     * ID of Textarea where editor will be placed
     *
     * @var string
     */
    protected $id;


    public function init()
    {
        parent::init();

        if (empty($this->id)) {
            $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }

        if (empty($this->leptureOptions['element'])) {
            $this->leptureOptions['element'] = new JsExpression('$("#' . $this->id . '")[0]');
        }

        $jsonOptionsMarked = Json::encode($this->markedOptions);
        $script = 'marked.setOptions(' . $jsonOptionsMarked . ');';
        $this->view->registerJs($script);
    }

    public function run()
    {
        MdEditorAsset::register($this->view);
        $this->registerScripts();

        $this->options['id'] = $this->id;
        if ($this->hasModel()) {
            $textarea = Html::activeTextArea($this->model, $this->attribute, $this->options);
        } else {
            $textarea = Html::textArea($this->name, $this->value, $this->options);
        }
        echo '<div class="lepture">' . $textarea . '</div>';
    }

    public function registerScripts()
    {
        $jsonOptions = Json::encode($this->leptureOptions);
        $varName = Inflector::classify('editor' . $this->id);

        $script = "var {$varName} = new Editor(" . $jsonOptions . "); {$varName}.render();";
        if(isset($this->options['rows'])) {
            $script .= "$('.CodeMirror').css('height', '" . $this->options['rows'] * 1.5 . "em');";
        }
        $this->view->registerJs($script);
    }
}