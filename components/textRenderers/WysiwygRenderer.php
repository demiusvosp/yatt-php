<?php
/**
 * User: demius
 * Date: 20.12.17
 * Time: 23:31
 */

namespace app\components\textRenderers;


use yii\base\Component;
use yii\helpers\HtmlPurifier;


class WysiwygRenderer extends Component implements ITextRenderer
{
    public function render($data)
    {
        return HtmlPurifier::process($data);
    }
}