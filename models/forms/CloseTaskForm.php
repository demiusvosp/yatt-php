<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 22.10.17
 * Time: 1:37
 */

namespace app\models\forms;

use Yii;
use elisdn\compositeForm\CompositeForm;
use app\models\entities\Comment;


/**
 * Class CloseTaskForm
 *
 * @property Comment $comment
 */
class CloseTaskForm extends CompositeForm
{

    public $task_id;

    public $close_reason;


    /**
     * @return array
     */
    public function internalForms()
    {
        return ['comment'];
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['close_reason', 'task_id'], 'required'],
            [['close_reason', 'task_id'], 'integer']
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'close_reason' => Yii::t('task', 'Close reason')
        ];
    }



    public function init()
    {
        $this->comment = new Comment([
            'author' => Yii::$app->user->identity,
            'object_id' => $this->task_id,
            'object_class' => 'app\models\entities\Task',
            'type' => Comment::TYPE_CLOSE
        ]);
        parent::init();
    }


    public function load($data, $formName = null)
    {
        if(parent::load($data, $formName)) {
            // перегружаем в коммент данные из формы закрытия
            $this->comment->object_class = 'app\models\entities\Task';
            $this->comment->object_id = $this->task_id;
            $this->comment->type = Comment::TYPE_CLOSE;

            return true;
        }

        return false;
    }


    public function saveComment()
    {
        if(empty($this->comment->text)) {
            $this->comment->text = Yii::t('task', 'Task closed');
        }
        $this->comment->save();
    }

}
