<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 26.09.17
 * Time: 14:12
 */

namespace app\models\forms;

use app\models\queries\TaskQuery;
use Yii;
use yii\base\Model;
use app\components\ProjectService;
use app\models\entities\Task;


/**
 * обертка над моделью-кнтейнером, дающая весь дополнительны функционал, и скрывающая детали контейнера
 * Class taskCreateForm
 * @package app\models\forms
 */
class TaskForm extends Model
{
    // поля формы
    public $caption;
    public $description;
    public $assigned;

    public $isNewRecord;

    /** @var  Task $model */
    protected $_model;

    /** @var ProjectService */
    protected $_projectService;

    function __construct(Task $task = null, array $config = [])
    {
        if($task) {
            $this->_model = $task;
            $this->isNewRecord = false;
        } else {
            $this->_model = new Task();
            $this->isNewRecord = true;
        }

        $this->_projectService = Yii::$app->projectService;
        parent::__construct($config);
    }


    public function rules()
    {
        return $this->_model->rules();// лучше задачть свои, это временная мера
    }

    public function attributeLabels()
    {
        // а вот тут лучше оставить лабелы модели, может быть дополнив своими.
        return $this->_model->attributeLabels();
    }

    /**
     * Сохранить задачу
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if($runValidation) {
            if(!$this->_model->validate($attributeNames)) {
                return false;
            }
        }

        $transaction = Task::getDb()->beginTransaction();
        try {
            // дополнительно обработаем входные данные и заведем таск
            $this->_model->caption = $this->caption;
            $this->_model->description = $this->description;

            // надо создать естественные ключи
            $this->_model->suffix = $this->_projectService->project->suffix;
            $this->_model->index = $this->_projectService->generateNewTaskId();
            $this->_model->save(false);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }

        return true;
    }

}