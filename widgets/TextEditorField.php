<?php
/**
 * User: demius
 * Date: 26.11.17
 * Time: 20:57
 */

namespace app\widgets;

use app\helpers\TextEditorHelper;
use Yii;
use yii\widgets\ActiveField;
use app\models\entities\IEditorType;


class TextEditorField extends ActiveField
{


    /**
     * отрисовтаь виджет с текстовым редактором (указанного типа или из модели)
     * @param null  $type
     * @param array $options
     * @return $this
     * @throws \Exception
     */
    public function editor($type = null, $options = [])
    {
        $editorType = null;
        if (is_string($type)) {
            $editorType = $type;
        } else if (is_array($type)) {
            if (isset($type['type'])) {
                $editorType = $type['type'];
            } else {
                $editorType = Yii::$app->params['defaultEditor'];
            }
            unset($type['type']);
            $options = $type;
        }

        if($this->model) {
            if ($this->model instanceof IEditorType) {
                $editorType = $this->model->getEditorType($this->attribute);
            } else {
                throw new \LogicException('Cannot get editor type to ' . $this->model->className() . '::' . $this->attribute);
            }
        }
        $options = array_merge($this->inputOptions, $options);

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

            $editorWidget = TextEditorHelper::getTextEditor($editorType, $config);

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
}
