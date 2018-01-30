<?php
/**
 * User: demius
 * Date: 20.12.17
 * Time: 23:30
 */

namespace app\components\textRenderers;


interface ITextRenderer
{
    /**
     * @param $data
     * @return string
     */
    public function render($data);
}