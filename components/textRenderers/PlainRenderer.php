<?php
/**
 * User: demius
 * Date: 20.12.17
 * Time: 23:30
 */

namespace app\components\textRenderers;


use yii\base\Component;


class PlainRenderer extends Component implements ITextRenderer
{
    public function render($data)
    {
        return $data;
    }
}