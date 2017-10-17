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

/**
 * Class DictEdit - Виджет управления справочником. Позволяет добавлять, удалять, менять порядок, и менять элементы
 *   справочника.
 *   Ввиду моего скудного знания фронта, он сильно завязан на классы и id'ы, поэтому несоклько ставить на одну страницу
 *   нельзя.
 * @package app\widgets
 */
class DictEdit extends Widget
{
    /** @var  DictForm */
    public $dictForm;

    public $template = 'dictEdit.twig';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render($this->template, [
            'dictForm' => $this->dictForm,
            'project' => $this->dictForm->project,
        ]);
    }

}