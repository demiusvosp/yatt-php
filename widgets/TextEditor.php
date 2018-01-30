<?php
/**
 * User: demius
 * Date: 26.11.17
 * Time: 20:57
 */

namespace app\widgets;

use app\components\textEditors\ATextEditor;
use app\models\entities\IEditorType;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveField;
use yii\widgets\InputWidget;


class TextEditor extends ActiveField
{


    public function editor($type = null, $options = [])
    {
        if(is_string($type)) {
            $editorType = $type;
        } else if(is_array($type)) {
            if(isset($type['type'])) {
                $editorType = $type['type'];
            } else {
                $editorType = Yii::$app->params['defaultEditor'];
            }
            unset($type['type']);
            $options = $type;
        } else if($this->model instanceof IEditorType) {
            $editorType = $this->model->getEditorType($this->attribute);
        } else {
            throw new \LogicException('Cannot get editor type to ' . $this->model->className() . '::' . $this->attribute);
        }

        $options = array_merge($this->inputOptions, $options);

        $config['class'] = $editorType . 'Editor';
        $config['options'] = $options;
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();
        $this->addAriaAttributes($config['options']);
        $this->adjustLabelFor($config['options']);

        // copy-paste из yii\base\Widget::widget() так как нам необходимо самостоятельно создать инстанс виджета, со своим конфигом
        ob_start();
        ob_implicit_flush(false);
        try {
            $out = '';
            /** @var ATextEditor $editorClass */
            $editorWidget = Yii::createObject($config);

            if ($editorWidget->beforeRun()) {
                $result = $editorWidget->run();
                $out = $editorWidget->afterRun($result);
            }
        } catch (\Exception $e) {
            // close the output buffer opened above if it has not been closed already
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }

        $this->parts['{input}'] = ob_get_clean() . $out;

        return $this;
    }


//    public function run()
//    {
//        $config = [
//            'name' => Html::getAttributeName($this->attribute),
////            'value' => Html::getAttributeValue($this->model, $this->attribute),
//            'attribute' => $this->attribute,
//            'model'     => $this->model,
//            'options'   => $this->options,
//        ];
//
////        /** @var Widget $editor */
////        $editor = Yii::createObject(
////            $this->type . 'Editor'
////        );
////        Yii::configure($editor, $config);
//        $editor = Yii::$container->get($this->type . 'Editor', [], $config);
//
//        if (!$editor) {
//            throw new \DomainException('Cannot create editor for type ' . $this->type);
//        }
//
//        return $editor->run();
//    }
}