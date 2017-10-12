<?php

namespace app\models\queries;

use Yii;
use app\models\entities\Project;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\models\entities\Project]].
 *
 * @see \app\models\entities\Project
 */
class ProjectQuery extends ActiveQuery
{
   /**
     * Толко имеющие указанный статус или более строгий
     * @param int $publicStatus
     * @return $this
     */
    public function andPublic($publicStatus = Project::STATUS_PUBLIC_ALL)
    {
        return $this->andWhere(['<=', 'public', $publicStatus]);
    }

    /**
     * Жадно загрузить вместе со справочниками
     * @param bool $dicts
     * @return $this
     */
    public function withDicts($dicts = false)
    {
        if(!$dicts) {
            $dicts = ['dict_state'];
        }
        return $this->with($dicts);
    }


    /**
     * Query проектов доступных текущему пользователю
     * @return ProjectQuery
     */
    static function allowProjectsQuery()
    {
        $query = Project::find(); // довольно странно получать базовое квери от ентити

        if (Yii::$app->user->identity == null) {
            // доступные только гостям.
            return $query->andPublic();
        } else {
            return $query->andPublic(Project::STATUS_PUBLIC_AUTHED);
            //@TODO пользователю должны найтись и проекты в которых его лично пустили полномочиями.
        }
    }

    /**
     * Количество проектов доступных текущему пользователю
     * @return int
     */
    static function countAllowProjects()
    {
        $query = static::allowProjectsQuery();

        return $query->count();
    }

    /**
     * Список проектов доступных пользователю
     * @param bool $forSelect false - [ ['id'=><id>, 'name'=><name>], ... ]; true - [ <id> => <name>, ... ]
     * @return array [<id> => <name>]
     */
    static function allowProjectsList($forSelect = false)
    {
        $query = static::allowProjectsQuery()->select(['id', 'suffix', 'name']);
        if($forSelect) {
            $query->indexBy('id');
        }

        $projects = $query->all();
        return $projects;
    }

    /**
     * @inheritdoc
     * @return \app\models\entities\Project[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\entities\Project|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byId($id)
    {
        if(is_string($id)) {
            return $this->where(['suffix' => $id])->one();
        }
        if(is_int($id)) {
            return $this->where(['id' => $id])->one();
        }
    }

}
