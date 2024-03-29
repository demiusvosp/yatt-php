<?php
/**
 * User: demius
 * Date: 23.10.17
 * Time: 22:18
 */

namespace app\widgets;


use yii\base\Widget;
use app\models\entities\Task;
use app\models\forms\CloseTaskForm;


/**
 * Class CloseTask
 *
 * Да, виджет кажется слишком простым, чтобы выделять в виджет, зато в несвязаном с действиями контролере нет тысячи
 *   форм модальных действий. Я бы еще валидаию средствами виджета делал, чтобы не размазывать функционал маленькой
 *   детали по двум классам
 *
 * @package app\widgets
 */
class CloseTask extends Widget
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
            'task_id' => $this->task->id,
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