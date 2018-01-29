<?php
/**
 * User: demius
 * Date: 20.12.17
 * Time: 23:30
 */

namespace app\components\textEditors;


use yii\helpers\Html;


class PlainEditor extends ATextEditor
{

    public function __construct(array $config = [])
    {
var_dump($config);
        parent::__construct($config);
    }


    public function init()
    {
var_dump($this);die();
        parent::init();
    }

    public function run()
    {
        return Html::textarea($this->name, $this->value, $this->options);
    }
}