<?php
/**
 * User: demius
 * Date: 20.12.17
 * Time: 23:30
 */

namespace app\components\textEditors;


use yii\widgets\InputWidget;
use yii\helpers\Html;


class PlainEditor extends InputWidget implements ITextEditor
{
    public function init()
    {
        parent::init();
    }


    public function run()
    {
        return Html::activeTextarea($this->model, $this->attribute, $this->options);
    }
}