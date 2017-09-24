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
            // пользователю должны найтись и проекты в которых его лично пустили полномочиями.
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
     * @return array [<id> => <name>]
     */
    static function allowProjectsList()
    {
        $projects = static::allowProjectsQuery()->select(['id', 'suffix', 'name'])->all();
        return $projects; // для меню хватит массива [ ['id'=><id>, 'name'=><name>], ... ]
//        $list = []; // для дропдовн нужен массив вида [ <id> => <name> ] но сейчас это нигле не используется
//        foreach ($projects as $project) {
//            $list[$project['id']] = $project['name'];
//        }
//        return $list;
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
}
