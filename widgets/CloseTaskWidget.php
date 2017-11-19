<?php
/**
 * User: demius
 * Date: 23.10.17
 * Time: 22:18
 */

namespace app\widgets;


use app\models\forms\CloseTaskForm;
use yii\jui\Widget;
use app\models\entities\Task;


/**
 * Class CloseTaskWidget
 *
 * Да, виджет кажется слишком простым, чтобы выделять в виджет, зато в несвязаном с действиями контролере нет тысячи
 *   форм модальных действий. Я бы еще валидаию средствами виджета делал, чтобы не размазывать функционал маленькой
 *   детали по двум классам
 *
 * @package app\widgets
 */
class CloseTaskWidget extends Widget
{

    /** @var  Task */
    public $task;

    /** @var  CloseTaskForm */
    public $closeForm;

    /** @var  string - id модального окна */
    public $modalId;


    public function init()
    {
        $this->closeForm = new CloseTaskForm([
            'index' => $this->task->index,
            'suffix' => $this->task->suffix
        ]);
    }


    public function run()
    {
        return $this->render(
            'closeTask',
            [
                'task' => $this->task,
                'model' => $this->closeForm,
                'modalId' => $this->modalId,
            ]
        );
    }
}