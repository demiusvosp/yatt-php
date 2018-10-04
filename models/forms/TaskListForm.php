<?php
/**
 * User: demius
 * Date: 01.10.18
 * Time: 23:21
 */

namespace app\models\forms;


use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use app\models\entities\Task;


/**
 * Хороший вопрос, нужна ли вобще нам эта форма? Что ей передается, кроме фиксированного набора полей?
 * Class TaskListForm
 *
 * @package app\models\forms
 */
class TaskListForm extends Model
{
    public $search = [];

    public $sort = ['id' => SORT_ASC];

    public $columns = [
        'id',
        'category',
        'type',
        'caption',
        'priority',
        'assigned',
        'stage',
        'progress',
        'created_at',
        'versionOpen',
        'update_at',
        'versionClose',
    ];

    public $project = null;

    protected $_query = null;
    protected $_dataProvider = null;


    /**
     * Получить Query для списка
     *
     * @return \app\models\queries\TaskQuery|null
     */
    public function getQuery()
    {
        if ($this->_query) {
            return $this->_query;
        }

        $queryParts   = [
            'assigned'     => function (ActiveQuery $query) {
                $query->from(['assigned' => 'user']);
            },
            'stage'        => function (ActiveQuery $query) {
                $query->from(['stage' => 'dict_stage']);
            },
            'type'         => function (ActiveQuery $query) {
                $query->from(['type' => 'dict_type']);
            },
            'category'     => function (ActiveQuery $query) {
                $query->from(['category' => 'dict_category']);
            },
            'versionOpen'  => function (ActiveQuery $query) {
                $query->from(['versionOpen' => 'dict_version']);
            },
            'versionClose' => function (ActiveQuery $query) {
                $query->from(['versionClose' => 'dict_version']);
            },
        ];
        $this->_query = Task::find();
        if ($this->project) {
            $this->_query->andProject($this->project);
        }
        foreach ($this->columns as $column) {
            if (isset($queryParts[$column])) {
                $this->_query->joinWith([$column => $queryParts[$column]]);
            }
        }

        return $this->_query;
    }


    /**
     * Построить DataProvider для списка задач
     *
     * @return null|ActiveDataProvider
     */
    public function getDataProvider()
    {
        if ($this->_dataProvider) {
            return $this->_dataProvider;
        }

        $this->_dataProvider = new ActiveDataProvider([
            'query' => $this->getQuery(),
        ]);

        $sorts = [
            'id'         => ['suffix', 'index'],
            'assigned'     => ['assigned.username'],
            'stage'        => ['stage.position'],
            'type'         => ['type.position'],
            'category'     => ['category.position'],
            'versionOpen'  => ['versionOpen.position'],
            'versionClose' => ['versionClose.position'],
        ];

        foreach ($this->columns as $column) {
            if (isset($sorts[$column])) {
                $sort = ['asc' => [], 'desc' => []];
                foreach ($sorts[$column] as $field) {
                    $sort['asc'][$field]  = SORT_ASC;
                    $sort['desc'][$field] = SORT_DESC;
                }
                $this->_dataProvider->sort->attributes[$column] = $sort;
            }
        }

        return $this->_dataProvider;
    }
}