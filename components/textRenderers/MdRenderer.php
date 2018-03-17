<?php
/**
 * User: demius
 * Date: 20.12.17
 * Time: 23:31
 */

namespace app\components\textRenderers;


use yii\base\Component;
use yii\helpers\Markdown;


class MdRenderer extends Component implements ITextRenderer
{
    public $flavor = '';

    public function render($data)
    {
        return Markdown::process($data, $this->flavor);
    }
}