<?php
/**
 * User: Dmitriy Vospennikov
 * Date: 31.01.18
 * Time: 12:22
 */

namespace app\helpers;

use app\components\textEditors\ATextEditor;
use Yii;
use app\components\textRenderers\ITextRenderer;
use app\models\entities\IEditorType;

class TextEditorHelper
{
    /**
     * @param string $type
     * @param array $config
     * @return ATextEditor
     * @throws \yii\base\InvalidConfigException
     */
    public static function getTextEditor($type, $config = [])
    {
        if(!in_array($type, Yii::$app->params['editorList'])) {
            throw new \InvalidArgumentException('Unknown textEditor type '.$type);
        }
        $type .= 'Editor';

        $config['class'] = $type;
        return Yii::createObject($config);
    }

    public static function getTextRenderer($type)
    {
        if(!in_array($type, Yii::$app->params['editorList'])) {
            throw new \InvalidArgumentException('Unknown textEditor type '.$type);
        }

        $type .= 'Renderer';

        return Yii::$container->get($type);
    }

    public static function getTextEditorsList()
    {
        $list = Yii::$app->params['editorList'];

        // добавим переводы


        return $list;
    }


    /**
     * @param string|IEditorType $type - editor type or model implemented IEditorType::getType
     * @param string|mixed $data - rendered value or model field who render
     * @return string
     */
    public static function render($type, $data)
    {

        if ($type instanceof IEditorType) {
            $data = $type->$data;
            $type = $type->getEditorType($type);
        }

        /** @var ITextRenderer $render */
        $render = static::getTextRenderer($type);

        return $render->render($data);
    }
}