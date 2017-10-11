<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 11.10.17
 * Time: 21:22
 */

namespace app\widgets;

use yii\base\Widget;

class DictEdit extends Widget
{
    public $dictForm;

    public function init()
    {
        parent::init();
    }


    public function run()
    {
        return $this->render('dictEdit', ['dictForm' => $this->dictForm]);
    }

}