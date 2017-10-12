<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 11.10.17
 * Time: 21:22
 */

namespace app\widgets;

use app\models\forms\DictForm;
use yii\base\Widget;

class DictEdit extends Widget
{
    /** @var  DictForm */
    public $dictForm;

    public function init()
    {
        parent::init();
    }


    public function run()
    {
        return $this->render('dictEdit', [
            'dictForm' => $this->dictForm,
            'project' => $this->dictForm->project,
        ]);
    }

}